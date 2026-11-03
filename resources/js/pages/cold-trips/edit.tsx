import ColdTripForm, { type ColdTripData } from '@/components/cold-trip-form';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

type Trip = {
    id: number;
    required_temp_c: string | null;
    min_logged_temp: string | null;
    max_logged_temp: string | null;
    dispatch_time: string | null;
    arrival_time: string | null;
    truck: { truck_plate: string | null } | null;
    driver: { driver_name: string | null } | null;
    cargo_type: { cargo_type: string | null } | null;
    departure_hub: { hub_name: string | null } | null;
    destination_hub: { hub_name: string | null } | null;
};

export default function EditColdTrip({ trip }: { trip: Trip }) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Cold Trips', href: '/cold-trips' },
        { title: `Edit trip #${trip.id}`, href: `/cold-trips/${trip.id}/edit` },
    ];
    const initialData: ColdTripData = {
        truck_plate: trip.truck?.truck_plate ?? '',
        driver_name: trip.driver?.driver_name ?? '',
        cargo_type: trip.cargo_type?.cargo_type ?? '',
        departure_hub: trip.departure_hub?.hub_name ?? '',
        destination_hub: trip.destination_hub?.hub_name ?? '',
        required_temp_c: trip.required_temp_c ?? '',
        min_logged_temp: trip.min_logged_temp ?? '',
        max_logged_temp: trip.max_logged_temp ?? '',
        dispatch_time: trip.dispatch_time?.slice(0, 16) ?? '',
        arrival_time: trip.arrival_time?.slice(0, 16) ?? '',
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit trip #${trip.id}`} />
            <div className="p-4 md:p-6">
                <Card className="mx-auto max-w-4xl">
                    <CardHeader>
                        <CardTitle>Edit cold trip #{trip.id}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <ColdTripForm initialData={initialData} submitLabel="Save changes" action={`/cold-trips/${trip.id}`} method="put" />
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
