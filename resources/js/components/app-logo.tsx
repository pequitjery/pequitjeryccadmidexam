import { Snowflake } from 'lucide-react';

export default function AppLogo() {
    return (
        <>
            <Snowflake className="size-6 shrink-0 text-white" aria-hidden="true" />
            <span className="truncate text-base leading-none font-semibold tracking-wide">COLD CHAIN TRACKER</span>
        </>
    );
}
