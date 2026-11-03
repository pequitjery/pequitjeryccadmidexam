import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

type Trip = {
    id: number;
    required_temp_c: string | null;
    min_logged_temp: string | null;
    max_logged_temp: string | null;
    dispatch_time: string | null;
    arrival_time: string | null;
    temp_compliance_status: string;
    truck: { truck_plate: string | null } | null;
    driver: { driver_name: string | null } | null;
    cargo_type: { cargo_type: string | null } | null;
    departure_hub: { hub_name: string | null } | null;
    destination_hub: { hub_name: string | null } | null;
};

export default function ShowColdTrip({ trip }: { trip: Trip }) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Cold Trips', href: '/cold-trips' },
        { title: `Trip #${trip.id}`, href: `/cold-trips/${trip.id}` },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Cold trip #${trip.id}`} />
            <div className="p-4 md:p-6">
                <Card className="mx-auto max-w-3xl">
                    <CardHeader className="flex flex-row items-center justify-between">
                        <CardTitle>Cold trip #{trip.id}</CardTitle>
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
                    </CardHeader>
                    <CardContent>
                        <dl className="grid gap-4 sm:grid-cols-2">
                            <div>
                                <dt className="text-muted-foreground text-sm">Truck</dt>
                                <dd className="font-medium">{trip.truck?.truck_plate ?? 'Unknown truck'}</dd>
                            </div>
                            <div>
                                <dt className="text-muted-foreground text-sm">Driver</dt>
                                <dd className="font-medium">{trip.driver?.driver_name ?? 'Unknown driver'}</dd>
                            </div>
                            <div>
                                <dt className="text-muted-foreground text-sm">Cargo</dt>
                                <dd className="font-medium">{trip.cargo_type?.cargo_type ?? 'Unknown cargo'}</dd>
                            </div>
                            <div>
                                <dt className="text-muted-foreground text-sm">Route</dt>
                                <dd className="font-medium">
                                    {trip.departure_hub?.hub_name ?? 'Unknown'} → {trip.destination_hub?.hub_name ?? 'Unknown'}
                                </dd>
                            </div>
                            <div>
                                <dt className="text-muted-foreground text-sm">Required temperature</dt>
                                <dd className="font-medium">{trip.required_temp_c ?? '—'}°C</dd>
                            </div>
                            <div>
                                <dt className="text-muted-foreground text-sm">Logged range</dt>
                                <dd className="font-medium">
                                    {trip.min_logged_temp ?? '—'}°C to {trip.max_logged_temp ?? '—'}°C
                                </dd>
                            </div>
                            <div>
                                <dt className="text-muted-foreground text-sm">Dispatch</dt>
                                <dd className="font-medium">{trip.dispatch_time ? new Date(trip.dispatch_time).toLocaleString() : 'Not recorded'}</dd>
                            </div>
                            <div>
                                <dt className="text-muted-foreground text-sm">Arrival</dt>
                                <dd className="font-medium">{trip.arrival_time ? new Date(trip.arrival_time).toLocaleString() : 'In progress'}</dd>
                            </div>
                        </dl>
                        <div className="mt-6 flex gap-3">
                            <Button asChild>
                                <Link href={`/cold-trips/${trip.id}/edit`}>Edit trip</Link>
                            </Button>
                            <Button variant="outline" asChild>
                                <Link href="/cold-trips">Back to trips</Link>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
