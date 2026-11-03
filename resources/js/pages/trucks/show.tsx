import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

type Trip = {
    id: number;
    temp_compliance_status: string;
    required_temp_c: string | null;
    driver: { driver_name: string | null } | null;
    cargo_type: { cargo_type: string | null } | null;
    departure_hub: { hub_name: string | null } | null;
    destination_hub: { hub_name: string | null } | null;
};

type Truck = {
    id: number;
    truck_plate: string | null;
    cold_trips: Trip[];
};

export default function ShowTruck({ truck }: { truck: Truck }) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Trucks', href: '/trucks' },
        { title: truck.truck_plate ?? 'Truck details', href: `/trucks/${truck.id}` },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Truck ${truck.truck_plate ?? truck.id}`} />
            <div className="space-y-5 p-4 md:p-6">
                <Card>
                    <CardHeader className="flex flex-row items-center justify-between">
                        <CardTitle>{truck.truck_plate ?? 'Truck details'}</CardTitle>
                        <Button asChild>
                            <Link href={`/trucks/${truck.id}/edit`}>Edit truck</Link>
                        </Button>
                    </CardHeader>
                    <CardContent>
                        <p className="text-muted-foreground text-sm">{truck.cold_trips.length} recorded cold trips</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Trip history</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        {truck.cold_trips.map((trip) => (
                            <Link
                                key={trip.id}
                                href={`/cold-trips/${trip.id}`}
                                className="flex flex-col justify-between gap-2 border-b pb-3 last:border-0 sm:flex-row sm:items-center"
                            >
                                <div>
                                    <p className="font-medium">
                                        {trip.driver?.driver_name ?? 'Unknown driver'} · {trip.cargo_type?.cargo_type ?? 'Unknown cargo'}
                                    </p>
                                    <p className="text-muted-foreground text-sm">
                                        {trip.departure_hub?.hub_name ?? 'Unknown'} → {trip.destination_hub?.hub_name ?? 'Unknown'} · required{' '}
                                        {trip.required_temp_c ?? '—'}°C
                                    </p>
                                </div>
                                <span className="text-sm">{trip.temp_compliance_status}</span>
                            </Link>
                        ))}
                        {!truck.cold_trips.length && <p className="text-muted-foreground text-sm">No trips have been assigned to this truck.</p>}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
