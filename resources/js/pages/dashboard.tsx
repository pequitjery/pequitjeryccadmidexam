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
    const variant = status === 'Compliant' ? 'default' : status === 'Pending' ? 'secondary' : 'destructive';

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
        { label: 'Compliant', value: compliantTrips, icon: CheckCircle2 },
        { label: 'Temperature alerts', value: temperatureAlerts, icon: AlertTriangle },
        { label: 'Pending readings', value: pendingTrips, icon: Clock3 },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex flex-col gap-6 p-4 md:p-6">
                <div className="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h1 className="text-2xl font-semibold">Cold chain dashboard</h1>
                        <p className="text-muted-foreground">Monitor refrigerated deliveries and temperature compliance.</p>
                    </div>
                    <Button asChild>
                        <Link href="/cold-trips/create">
                            <Plus className="mr-2 size-4" />
                            Record a trip
                        </Link>
                    </Button>
                </div>

                <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                    {stats.map(({ label, value, icon: Icon }) => (
                        <Card key={label}>
                            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle className="text-sm font-medium">{label}</CardTitle>
                                <Icon className="text-muted-foreground size-4" />
                            </CardHeader>
                            <CardContent>
                                <p className="text-2xl font-bold">{value}</p>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                <div className="grid gap-6 xl:grid-cols-2">
                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <Thermometer className="text-destructive size-5" />
                                Temperature excursion alerts
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-3">
                            {alertTrips.length ? (
                                alertTrips.map((trip) => (
                                    <Link
                                        key={trip.id}
                                        href={`/cold-trips/${trip.id}`}
                                        className="border-destructive/30 flex flex-col justify-between gap-2 rounded-md border p-4 sm:flex-row sm:items-center"
                                    >
                                        <div>
                                            <p className="font-medium">
                                                {trip.truck?.truck_plate ?? 'Truck unavailable'} ·{' '}
                                                {trip.cargo_type?.cargo_type ?? 'Cargo unavailable'}
                                            </p>
                                            <p className="text-muted-foreground text-sm">
                                                {trip.driver?.driver_name ?? 'No driver'} · max {trip.max_logged_temp ?? '—'}°C / required{' '}
                                                {trip.required_temp_c ?? '—'}°C
                                            </p>
                                        </div>
                                        <StatusBadge status={trip.temp_compliance_status} />
                                    </Link>
                                ))
                            ) : (
                                <p className="text-muted-foreground text-sm">No temperature excursions recorded.</p>
                            )}
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between">
                            <CardTitle>Recent trips</CardTitle>
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
                                        className="flex flex-col justify-between gap-2 border-b pb-3 last:border-0 sm:flex-row sm:items-center"
                                    >
                                        <div>
                                            <p className="font-medium">
                                                {trip.truck?.truck_plate ?? 'Truck unavailable'} · {trip.driver?.driver_name ?? 'No driver'}
                                            </p>
                                            <p className="text-muted-foreground text-sm">
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
            </div>
        </AppLayout>
    );
}
