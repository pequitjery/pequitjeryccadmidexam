import TruckForm from '@/components/truck-form';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Trucks', href: '/trucks' },
    { title: 'Register truck', href: '/trucks/create' },
];

export default function CreateTruck() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Register truck" />
            <div className="p-4 md:p-6">
                <Card className="mx-auto max-w-xl">
                    <CardHeader>
                        <CardTitle>Register a refrigerated truck</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <TruckForm action="/trucks" method="post" submitLabel="Register truck" />
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
