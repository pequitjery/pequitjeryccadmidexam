import ColdTripForm from '@/components/cold-trip-form';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Cold Trips', href: '/cold-trips' },
    { title: 'New trip', href: '/cold-trips/create' },
];

export default function CreateColdTrip() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Record cold trip" />
            <div className="p-4 md:p-6">
                <Card className="mx-auto max-w-4xl">
                    <CardHeader>
                        <CardTitle>Record a cold trip</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <ColdTripForm submitLabel="Create trip" action="/cold-trips" method="post" />
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
