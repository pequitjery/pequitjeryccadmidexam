import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Plus } from 'lucide-react';

type CatalogItem = {
    id: number;
    name: string;
    cold_trips_count: number;
};

type Paginator = {
    data: CatalogItem[];
    links: { url: string | null; label: string; active: boolean }[];
};

type Props = {
    title: string;
    singular: string;
    basePath: string;
    items: Paginator;
    flash?: { success?: string; error?: string };
};

export default function CatalogIndex({ title, singular, basePath, items, flash }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/dashboard' },
        { title, href: basePath },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="space-y-5 p-4 md:p-6">
                <div className="flex items-center justify-between gap-3">
                    <div>
                        <h1 className="text-2xl font-semibold">{title}</h1>
                        <p className="text-muted-foreground">Manage {title.toLowerCase()} for your cold-chain fleet.</p>
                    </div>
                    <Button asChild>
                        <Link href={`${basePath}/create`}>
                            <Plus className="mr-2 size-4" />
                            Add {singular}
                        </Link>
                    </Button>
                </div>

                {flash?.success && (
                    <p role="status" className="bg-muted rounded-md p-3 text-sm">
                        {flash.success}
                    </p>
                )}
                {flash?.error && (
                    <p role="alert" className="bg-muted rounded-md p-3 text-sm">
                        {flash.error}
                    </p>
                )}

                <Card>
                    <CardHeader>
                        <CardTitle>{title}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-sm">
                                <thead>
                                    <tr className="text-muted-foreground border-b">
                                        <th className="p-3">{singular} name</th>
                                        <th className="p-3">Cold trips</th>
                                        <th className="p-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {items.data.map((item) => (
                                        <tr key={item.id} className="border-b last:border-0">
                                            <td className="p-3 font-medium">{item.name}</td>
                                            <td className="p-3">{item.cold_trips_count}</td>
                                            <td className="p-3">
                                                <div className="flex gap-2">
                                                    <Button variant="outline" size="sm" asChild>
                                                        <Link href={`${basePath}/${item.id}/edit`}>Edit</Link>
                                                    </Button>
                                                    <Button
                                                        variant="outline"
                                                        size="sm"
                                                        className="border-red-500 text-red-600 hover:bg-red-50 hover:text-red-700"
                                                        disabled={item.cold_trips_count > 0}
                                                        title={item.cold_trips_count ? 'Cannot delete an entry used by cold trips' : undefined}
                                                        onClick={() => {
                                                            if (window.confirm(`Delete ${singular.toLowerCase()} "${item.name}"?`)) {
                                                                router.delete(`${basePath}/${item.id}`);
                                                            }
                                                        }}
                                                    >
                                                        Delete
                                                    </Button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                    {!items.data.length && (
                                        <tr>
                                            <td className="text-muted-foreground p-6 text-center" colSpan={3}>
                                                No {title.toLowerCase()} have been added yet.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>

                        <nav aria-label={`${title} pagination`} className="mt-4 flex flex-wrap gap-2">
                            {items.links.map((link, index) => {
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
