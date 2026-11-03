import TruckForm from '@/components/truck-form';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

type Truck = { id: number; truck_plate: string | null };

export default function EditTruck({ truck }: { truck: Truck }) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Trucks', href: '/trucks' },
        { title: `Edit ${truck.truck_plate ?? 'truck'}`, href: `/trucks/${truck.id}/edit` },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit truck" />
            <div className="p-4 md:p-6">
                <Card className="mx-auto max-w-xl">
                    <CardHeader>
                        <CardTitle>Edit truck</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <TruckForm truckPlate={truck.truck_plate ?? ''} action={`/trucks/${truck.id}`} method="put" submitLabel="Save changes" />
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
