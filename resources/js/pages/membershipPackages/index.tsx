import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { MembershipPackage} from '@/types';
import { Head } from '@inertiajs/react';
import { Link } from '@inertiajs/react'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Paket Membership',
        href: '/membership-packages',
    },
];

export default function MembershipPackages({ packages }: { packages: MembershipPackage[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Paket Membership" />
            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                {packages.map((pkg) => (
                    <Link
                        href={`/membership-packages/${pkg.slug}`}
                        key={pkg.id}
                        className="bg-white dark:bg-neutral-900 shadow-md rounded-2xl overflow-hidden border border-gray-200 dark:border-neutral-700 hover:shadow-lg transition-shadow block"
                    >
                        {pkg.images && pkg.images.length > 0 && (
                            <img
                                src={`/storage/membership_package/${pkg.images[0]}`}
                                alt={pkg.name}
                                className="w-full h-48 object-cover"
                            />
                        )}
                        <div className="p-6 space-y-2">
                            <h2 className="text-xl font-bold text-gray-800 dark:text-white">{pkg.name}</h2>
                            <p className="text-sm text-gray-500 dark:text-gray-400">{pkg.code}</p>
                            <p className="text-sm text-gray-600 dark:text-gray-300">{pkg.description}</p>
                            <div className="mt-2">
                                <p className="text-lg font-semibold text-primary">Rp {pkg.price.toLocaleString()}</p>
                                <p className="text-sm text-gray-500 dark:text-gray-400">Durasi: {pkg.duration} hari</p>
                                <p className="text-sm text-gray-500 dark:text-gray-400">({pkg.duration_in_months} bulan)</p>
                            </div>
                        </div>
                    </Link>
                ))}
            </div>
        </AppLayout>
    );
}
