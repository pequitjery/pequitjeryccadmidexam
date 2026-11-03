import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Plus } from 'lucide-react';

type Trip = {
    id: number;
    required_temp_c: string | null;
    max_logged_temp: string | null;
    temp_compliance_status: string;
    truck: { truck_plate: string | null } | null;
    driver: { driver_name: string | null } | null;
    cargo_type: { cargo_type: string | null } | null;
    departure_hub: { hub_name: string | null } | null;
    destination_hub: { hub_name: string | null } | null;
};

type Paginator = {
    data: Trip[];
    links: { url: string | null; label: string; active: boolean }[];
};

type Props = { trips: Paginator; flash?: { success?: string } };
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Cold Trips', href: '/cold-trips' },
];

export default function ColdTripsIndex({ trips, flash }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Cold trips" />
            <div className="space-y-5 p-4 md:p-6">
                <div className="flex items-center justify-between gap-3">
                    <div>
                        <h1 className="text-2xl font-semibold">Cold trips</h1>
                        <p className="text-muted-foreground">Manage delivery routes and temperature records.</p>
                    </div>
                    <Button asChild>
                        <Link href="/cold-trips/create">
                            <Plus className="mr-2 size-4" />
                            New trip
                        </Link>
                    </Button>
                </div>
                {flash?.success && (
                    <p role="status" className="bg-muted rounded-md p-3 text-sm">
                        {flash.success}
                    </p>
                )}
                <Card>
                    <CardHeader>
                        <CardTitle>Trip records</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-sm">
                                <thead>
                                    <tr className="text-muted-foreground border-b">
                                        <th className="p-3">Truck</th>
                                        <th className="p-3">Driver / cargo</th>
                                        <th className="p-3">Route</th>
                                        <th className="p-3">Temperature</th>
                                        <th className="p-3">Status</th>
                                        <th className="p-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {trips.data.map((trip) => (
                                        <tr key={trip.id} className="border-b last:border-0">
                                            <td className="p-3 font-medium">{trip.truck?.truck_plate ?? 'Unknown truck'}</td>
                                            <td className="p-3">
                                                {trip.driver?.driver_name ?? 'Unknown driver'}
                                                <br />
                                                <span className="text-muted-foreground">{trip.cargo_type?.cargo_type ?? 'Unknown cargo'}</span>
                                            </td>
                                            <td className="p-3">
                                                {trip.departure_hub?.hub_name ?? 'Unknown'} → {trip.destination_hub?.hub_name ?? 'Unknown'}
                                            </td>
                                            <td className="p-3">
                                                Req. {trip.required_temp_c ?? '—'}°C
                                                <br />
                                                <span className="text-muted-foreground">Max {trip.max_logged_temp ?? '—'}°C</span>
                                            </td>
                                            <td className="p-3">
                                                <Badge
                                                    variant={
                                                        trip.temp_compliance_status === 'Compliant'
                                                            ? 'default'
                                                            : trip.temp_compliance_status === 'Pending'
                                                              ? 'secondary'
                                                              : 'destructive'
                                                    }
                                                >
                                                    {trip.temp_compliance_status}
                                                </Badge>
                                            </td>
                                            <td className="p-3">
                                                <div className="flex gap-2">
                                                    <Button variant="outline" size="sm" asChild>
                                                        <Link href={`/cold-trips/${trip.id}`}>View</Link>
                                                    </Button>
                                                    <Button variant="outline" size="sm" asChild>
                                                        <Link href={`/cold-trips/${trip.id}/edit`}>Edit</Link>
                                                    </Button>
                                                    <Button variant="destructive" size="sm" onClick={() => router.delete(`/cold-trips/${trip.id}`)}>
                                                        Delete
                                                    </Button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                    {!trips.data.length && (
                                        <tr>
                                            <td className="text-muted-foreground p-6 text-center" colSpan={6}>
                                                No cold trips recorded yet.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                        <nav aria-label="Pagination" className="mt-4 flex flex-wrap gap-2">
                            {trips.links.map((link, index) => (
                                <Button
                                    key={`${index}-${link.label}`}
                                    variant={link.active ? 'default' : 'outline'}
                                    size="sm"
                                    disabled={!link.url}
                                    asChild={Boolean(link.url)}
                                >
                                    {link.url ? (
                                        <Link href={link.url} preserveScroll>
                                            {link.label.replaceAll('&laquo;', '«').replaceAll('&raquo;', '»')}
                                        </Link>
                                    ) : (
                                        <span>{link.label.replaceAll('&laquo;', '«').replaceAll('&raquo;', '»')}</span>
                                    )}
                                </Button>
                            ))}
                        </nav>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
