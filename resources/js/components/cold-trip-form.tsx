import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Link, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

export type ColdTripData = {
    truck_plate: string;
    driver_name: string;
    cargo_type: string;
    departure_hub: string;
    destination_hub: string;
    required_temp_c: string;
    min_logged_temp: string;
    max_logged_temp: string;
    dispatch_time: string;
    arrival_time: string;
};

type Props = {
    initialData?: ColdTripData;
    submitLabel: string;
    action: string;
    method: 'post' | 'put';
};

const emptyData: ColdTripData = {
    truck_plate: '',
    driver_name: '',
    cargo_type: '',
    departure_hub: '',
    destination_hub: '',
    required_temp_c: '',
    min_logged_temp: '',
    max_logged_temp: '',
    dispatch_time: '',
    arrival_time: '',
};

const fields: { key: keyof ColdTripData; label: string; type?: string; required?: boolean; step?: string }[] = [
    { key: 'truck_plate', label: 'Truck plate', required: true },
    { key: 'driver_name', label: 'Driver', required: true },
    { key: 'cargo_type', label: 'Cargo type', required: true },
    { key: 'departure_hub', label: 'Departure hub', required: true },
    { key: 'destination_hub', label: 'Destination hub', required: true },
    { key: 'required_temp_c', label: 'Required temperature (°C)', type: 'number', required: true, step: '0.01' },
    { key: 'min_logged_temp', label: 'Minimum logged temperature (°C)', type: 'number', step: '0.01' },
    { key: 'max_logged_temp', label: 'Maximum logged temperature (°C)', type: 'number', step: '0.01' },
    { key: 'dispatch_time', label: 'Dispatch time', type: 'datetime-local', required: true },
    { key: 'arrival_time', label: 'Arrival time', type: 'datetime-local' },
];

export default function ColdTripForm({ initialData = emptyData, submitLabel, action, method }: Props) {
    const form = useForm<ColdTripData>(initialData);

    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        form[method](action);
    }

    return (
        <form onSubmit={submit} className="space-y-6">
            <div className="grid gap-5 sm:grid-cols-2">
                {fields.map(({ key, label, type = 'text', required, step }) => (
                    <div key={key} className="space-y-2">
                        <Label htmlFor={key}>{label}</Label>
                        <Input
                            id={key}
                            name={key}
                            type={type}
                            step={step}
                            required={required}
                            value={form.data[key]}
                            onChange={(event) => form.setData(key, event.target.value)}
                            aria-invalid={Boolean(form.errors[key])}
                        />
                        {form.errors[key] && <p className="text-destructive text-sm">{form.errors[key]}</p>}
                    </div>
                ))}
            </div>
            <div className="flex items-center gap-3">
                <Button type="submit" disabled={form.processing}>
                    {form.processing ? 'Saving…' : submitLabel}
                </Button>
                <Button type="button" variant="outline" asChild>
                    <Link href="/cold-trips">Cancel</Link>
                </Button>
            </div>
        </form>
    );
}
