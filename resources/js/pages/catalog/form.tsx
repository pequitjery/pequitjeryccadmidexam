import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { type FormEvent } from 'react';

type CatalogField = 'driver_name' | 'cargo_type' | 'hub_name';
type CatalogFormData = Record<CatalogField, string>;

type Props = {
    title: string;
    collectionTitle: string;
    singular: string;
    basePath: string;
    fieldName: CatalogField;
    fieldLabel: string;
    action: string;
    method: 'post' | 'put';
    value: string;
};

export default function CatalogForm({ title, collectionTitle, singular, basePath, fieldName, fieldLabel, action, method, value }: Props) {
    const form = useForm<CatalogFormData>({
        driver_name: fieldName === 'driver_name' ? value : '',
        cargo_type: fieldName === 'cargo_type' ? value : '',
        hub_name: fieldName === 'hub_name' ? value : '',
    });
    const error = form.errors[fieldName];
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/dashboard' },
        { title: collectionTitle, href: basePath },
        { title, href: action },
    ];

    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();

        form.transform((data) => ({ [fieldName]: data[fieldName] }));

        if (method === 'post') {
            form.post(action);
        } else {
            form.put(action);
        }
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="p-4 md:p-6">
                <Card className="mx-auto max-w-xl">
                    <CardHeader>
                        <CardTitle>{title}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className="space-y-5">
                            <div className="space-y-2">
                                <Label htmlFor={fieldName}>{fieldLabel}</Label>
                                <Input
                                    id={fieldName}
                                    name={fieldName}
                                    value={form.data[fieldName]}
                                    onChange={(event) => form.setData(fieldName, event.target.value)}
                                    required
                                    maxLength={255}
                                    autoFocus
                                    aria-invalid={Boolean(error)}
                                />
                                {error && (
                                    <p role="alert" className="text-sm text-neutral-700">
                                        {error}
                                    </p>
                                )}
                            </div>
                            <div className="flex gap-3">
                                <Button type="submit" disabled={form.processing}>
                                    {form.processing ? 'Saving…' : `Save ${singular.toLowerCase()}`}
                                </Button>
                                <Button type="button" variant="outline" asChild>
                                    <Link href={basePath}>Cancel</Link>
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
