import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Plus } from 'lucide-react';

type Truck = {
    id: number;
    truck_plate: string | null;
    cold_trips_count: number;
};

type Paginator = {
    data: Truck[];
    links: { url: string | null; label: string; active: boolean }[];
};

type Props = {
    trucks: Paginator;
    flash?: { success?: string; error?: string };
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Trucks', href: '/trucks' },
];

export default function TrucksIndex({ trucks, flash }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Trucks" />
            <div className="space-y-5 p-4 md:p-6">
                <div className="flex items-center justify-between gap-3">
                    <div>
                        <h1 className="text-2xl font-semibold">Trucks</h1>
                        <p className="text-muted-foreground">Manage refrigerated vehicles in your fleet.</p>
                    </div>
                    <Button asChild>
                        <Link href="/trucks/create">
                            <Plus className="mr-2 size-4" />
                            Register truck
                        </Link>
                    </Button>
                </div>
                {flash?.success && (
                    <p role="status" className="bg-muted rounded-md p-3 text-sm">
                        {flash.success}
                    </p>
                )}
                {flash?.error && (
                    <p role="alert" className="bg-destructive/10 text-destructive rounded-md p-3 text-sm">
                        {flash.error}
                    </p>
                )}
                <Card>
                    <CardHeader>
                        <CardTitle>Fleet</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-sm">
                                <thead>
                                    <tr className="text-muted-foreground border-b">
                                        <th className="p-3">License plate</th>
                                        <th className="p-3">Cold trips</th>
                                        <th className="p-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {trucks.data.map((truck) => (
                                        <tr key={truck.id} className="border-b last:border-0">
                                            <td className="p-3 font-medium">{truck.truck_plate ?? 'Plate not recorded'}</td>
                                            <td className="p-3">{truck.cold_trips_count}</td>
                                            <td className="p-3">
                                                <div className="flex gap-2">
                                                    <Button variant="outline" size="sm" asChild>
                                                        <Link href={`/trucks/${truck.id}`}>View</Link>
                                                    </Button>
                                                    <Button variant="outline" size="sm" asChild>
                                                        <Link href={`/trucks/${truck.id}/edit`}>Edit</Link>
                                                    </Button>
                                                    <Button
                                                        variant="destructive"
                                                        size="sm"
                                                        disabled={truck.cold_trips_count > 0}
                                                        title={truck.cold_trips_count ? 'Cannot delete a truck with cold trips' : undefined}
                                                        onClick={() => {
                                                            if (window.confirm(`Delete truck ${truck.truck_plate ?? ''}?`)) {
                                                                router.delete(`/trucks/${truck.id}`);
                                                            }
                                                        }}
                                                    >
                                                        Delete
                                                    </Button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                    {!trucks.data.length && (
                                        <tr>
                                            <td className="text-muted-foreground p-6 text-center" colSpan={3}>
                                                No trucks registered yet.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                        <nav aria-label="Pagination" className="mt-4 flex flex-wrap gap-2">
                            {trucks.links.map((link, index) => {
                                const label = link.label.replaceAll('&laquo;', '«').replaceAll('&raquo;', '»');

                                return (
                                    <Button
                                        key={`${index}-${label}`}
                                        variant={link.active ? 'default' : 'outline'}
                                        size="sm"
                                        disabled={!link.url}
                                        asChild={Boolean(link.url)}
                                    >
                                        {link.url ? (
                                            <Link href={link.url} preserveScroll>
                                                {label}
                                            </Link>
                                        ) : (
                                            <span>{label}</span>
                                        )}
                                    </Button>
                                );
                            })}
                        </nav>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
