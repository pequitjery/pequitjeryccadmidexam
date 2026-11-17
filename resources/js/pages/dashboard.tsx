import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { AlertTriangle, CheckCircle2, Clock3, Package, Plus, Thermometer, Truck } from 'lucide-react';

type Trip = {
    id: number;
    required_temp_c: string | null;
    max_logged_temp: string | null;
    temp_compliance_status: string;
    truck: { truck_plate: string } | null;
    driver: { driver_name: string } | null;
    cargo_type: { cargo_type: string } | null;
    departure_hub: { hub_name: string } | null;
    destination_hub: { hub_name: string } | null;
};

type DashboardProps = {
    totalTrucks: number;
    totalTrips: number;
    compliantTrips: number;
    temperatureAlerts: number;
    pendingTrips: number;
    alertTrips: Trip[];
    recentTrips: Trip[];
};

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: '/dashboard' }];

function StatusBadge({ status }: { status: string }) {
    const variant = status === 'Accept' ? 'default' : 'secondary';

    return <Badge variant={variant}>{status}</Badge>;
}

export default function Dashboard({
    totalTrucks,
    totalTrips,
    compliantTrips,
    temperatureAlerts,
    pendingTrips,
    alertTrips,
    recentTrips,
}: DashboardProps) {
    const stats = [
        { label: 'Total trucks', value: totalTrucks, icon: Truck },
        { label: 'Cold trips', value: totalTrips, icon: Package },
        { label: 'Accepted', value: compliantTrips, icon: CheckCircle2 },
        { label: 'Temperature alerts', value: temperatureAlerts, icon: AlertTriangle },
        { label: 'Pending readings', value: pendingTrips, icon: Clock3 },
    ];

    const complianceRate = totalTrips > 0 ? Math.round((compliantTrips / totalTrips) * 100) : 0;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex flex-col gap-6 p-4 md:p-6">
                <div className="overflow-hidden rounded-3xl border border-neutral-800 bg-black p-6 text-white shadow-lg shadow-black/10">
                    <div className="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p className="mb-2 text-sm font-medium uppercase tracking-[0.2em] text-neutral-300">Fleet overview</p>
                            <h1 className="text-3xl font-bold tracking-tight">Cold chain dashboard</h1>
                            <p className="mt-2 max-w-xl text-sm text-neutral-300">
                                Monitor refrigerated deliveries, performance trends, and temperature compliance across your fleet.
                            </p>
                        </div>

                        <div className="flex items-center gap-3">
                            <div className="rounded-2xl border border-white/20 bg-white/10 px-4 py-3 backdrop-blur-sm">
                                <p className="text-xs uppercase tracking-wide text-neutral-300">Compliance</p>
                                <p className="mt-1 text-2xl font-bold">{complianceRate}%</p>
                            </div>
                            <Button asChild className="bg-white text-black hover:bg-neutral-200">
                                <Link href="/cold-trips/create">
                                    <Plus className="mr-2 size-4" />
                                    Record a trip
                                </Link>
                            </Button>
                        </div>
                    </div>
                </div>

                <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                    {stats.map(({ label, value, icon: Icon }) => (
                        <Card key={label} className="border-0 bg-white shadow-sm ring-1 ring-neutral-200">
                            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle className="text-sm font-medium text-neutral-600">{label}</CardTitle>
                                <div className="rounded-lg bg-neutral-100 p-2 text-neutral-900">
                                    <Icon className="size-4" />
                                </div>
                            </CardHeader>
                            <CardContent>
                                <p className="text-3xl font-bold tracking-tight text-neutral-950">{value}</p>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                <div className="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                    <Card className="border-0 bg-white shadow-sm ring-1 ring-neutral-200">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2 text-lg">
                                <Thermometer className="size-5 text-neutral-900" />
                                Temperature excursion alerts
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-3">
                            {alertTrips.length ? (
                                alertTrips.map((trip) => (
                                    <Link
                                        key={trip.id}
                                        href={`/cold-trips/${trip.id}`}
                                        className="flex flex-col justify-between gap-3 rounded-2xl border border-neutral-200 bg-neutral-50 p-4 transition hover:border-neutral-400 hover:bg-neutral-100 sm:flex-row sm:items-center"
                                    >
                                        <div>
                                            <p className="font-semibold text-neutral-900">
                                                {trip.truck?.truck_plate ?? 'Truck unavailable'} ·{' '}
                                                {trip.cargo_type?.cargo_type ?? 'Cargo unavailable'}
                                            </p>
                                            <p className="mt-1 text-sm text-neutral-600">
                                                {trip.driver?.driver_name ?? 'No driver'} · max {trip.max_logged_temp ?? '—'}°C / required{' '}
                                                {trip.required_temp_c ?? '—'}°C
                                            </p>
                                        </div>
                                        <StatusBadge status={trip.temp_compliance_status} />
                                    </Link>
                                ))
                            ) : (
                                <p className="rounded-xl border border-neutral-200 bg-neutral-50 p-4 text-sm text-neutral-600">
                                    No temperature excursions recorded.
                                </p>
                            )}
                        </CardContent>
                    </Card>

                    <Card className="border-0 bg-neutral-950 text-white shadow-lg shadow-neutral-300">
                        <CardHeader className="flex flex-row items-center justify-between">
                            <CardTitle className="text-lg text-white">Operations snapshot</CardTitle>
                            <span className="rounded-full bg-white/10 px-2.5 py-1 text-xs uppercase tracking-wide text-neutral-100">Live</span>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p className="text-sm text-neutral-300">Route health</p>
                                <div className="mt-3 flex items-end justify-between">
                                    <span className="text-3xl font-bold">{complianceRate}%</span>
                                    <span className="rounded-full bg-white/10 px-2 py-1 text-xs font-medium text-white">Stable</span>
                                </div>
                            </div>

                            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                                <div className="rounded-2xl border border-white/10 bg-white/5 p-4">
                                    <p className="text-sm text-neutral-300">Alerts</p>
                                    <p className="mt-2 text-2xl font-bold text-white">{temperatureAlerts}</p>
                                </div>
                                <div className="rounded-2xl border border-white/10 bg-white/5 p-4">
                                    <p className="text-sm text-neutral-300">Pending</p>
                                    <p className="mt-2 text-2xl font-bold text-white">{pendingTrips}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <Card className="border-0 bg-white shadow-sm ring-1 ring-neutral-200">
                    <CardHeader className="flex flex-row items-center justify-between">
                        <CardTitle className="text-lg">Recent trips</CardTitle>
                        <Button variant="outline" size="sm" asChild>
                            <Link href="/cold-trips">View all</Link>
                        </Button>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        {recentTrips.length ? (
                            recentTrips.map((trip) => (
                                <Link
                                    key={trip.id}
                                    href={`/cold-trips/${trip.id}`}
                                    className="flex flex-col justify-between gap-2 rounded-2xl border border-neutral-200 bg-neutral-50 p-3 transition hover:border-neutral-400 hover:bg-neutral-100 sm:flex-row sm:items-center"
                                >
                                    <div>
                                        <p className="font-semibold text-neutral-900">
                                            {trip.truck?.truck_plate ?? 'Truck unavailable'} · {trip.driver?.driver_name ?? 'No driver'}
                                        </p>
                                        <p className="mt-1 text-sm text-neutral-600">
                                            {trip.departure_hub?.hub_name ?? 'Unknown'} → {trip.destination_hub?.hub_name ?? 'Unknown'} ·{' '}
                                            {trip.cargo_type?.cargo_type ?? 'Cargo unavailable'}
                                        </p>
                                    </div>
                                    <StatusBadge status={trip.temp_compliance_status} />
                                </Link>
                            ))
                        ) : (
                            <p className="text-muted-foreground text-sm">No trips have been recorded yet.</p>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
