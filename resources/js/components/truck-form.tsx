import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Link, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

type TruckData = {
    truck_plate: string;
};

type Props = {
    truckPlate?: string;
    action: string;
    method: 'post' | 'put';
    submitLabel: string;
};

export default function TruckForm({ truckPlate = '', action, method, submitLabel }: Props) {
    const form = useForm<TruckData>({ truck_plate: truckPlate });

    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        form[method](action);
    }

    return (
        <form onSubmit={submit} className="space-y-5">
            <div className="space-y-2">
                <Label htmlFor="truck_plate">License plate</Label>
                <Input
                    id="truck_plate"
                    name="truck_plate"
                    value={form.data.truck_plate}
                    onChange={(event) => form.setData('truck_plate', event.target.value)}
                    required
                    maxLength={32}
                    autoFocus
                    aria-invalid={Boolean(form.errors.truck_plate)}
                />
                {form.errors.truck_plate && <p className="text-destructive text-sm">{form.errors.truck_plate}</p>}
            </div>
            <div className="flex gap-3">
                <Button type="submit" disabled={form.processing}>
                    {form.processing ? 'Saving…' : submitLabel}
                </Button>
                <Button type="button" variant="outline" asChild>
                    <Link href="/trucks">Cancel</Link>
                </Button>
            </div>
        </form>
    );
}
