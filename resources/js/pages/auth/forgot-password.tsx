import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import AppearanceToggle from '@/components/appearance-compact';

export default function ForgotPassword({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm<Required<{ email: string }>>({
        email: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.email'));
    };

    return (
        <>
            <Head title="Forgot Password" />
            <div className="h-screen md:flex">
                {/* Appearance Toggle Positioned Top-Right */}
                <div className="absolute top-4 right-4 z-50">
                    <AppearanceToggle />
                </div>

                {/* Left Panel */}
                <div
                    className="relative overflow-hidden hidden md:flex w-1/2 bg-cover bg-center justify-around items-center"
                    style={{ backgroundImage: "url('/images/bg.jpg')" }}
                >
                    <div className="absolute -bottom-32 -left-40 w-80 h-80 border-4 rounded-full border-opacity-30 border-white border-t-8"></div>
                    <div className="absolute -bottom-40 -left-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-white border-t-8"></div>
                    <div className="absolute -top-40 -right-0 w-80 h-80 border-4 rounded-full border-opacity-30 border-white border-t-8"></div>
                    <div className="absolute -top-20 -right-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-white border-t-8"></div>
                </div>

                {/* Right Panel with dark mode */}
                <div className="flex md:w-1/2 justify-center py-10 items-center bg-white dark:bg-black">
                    <form onSubmit={submit} className="w-full max-w-md px-6">
                        <h1 className="text-gray-800 dark:text-gray-200 font-bold text-2xl mb-1">Forgot Password</h1>
                        <p className="text-sm font-normal text-gray-600 dark:text-gray-400 mb-6">
                            Enter your email to receive a password reset link
                        </p>

                        {status && (
                            <div className="mb-4 text-center text-sm font-medium text-green-600 dark:text-green-400">
                                {status}
                            </div>
                        )}

                        <div className="space-y-4">
                            {/* Email Input with dark mode */}
                            <div className="flex items-center border-2 py-2 px-3 rounded-2xl text-black dark:text-white border-gray-300 dark:border-gray-700 bg-white dark:bg-black">
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    placeholder="Email address"
                                    className="w-full pl-2 outline-none border-none placeholder-gray-600 dark:placeholder-gray-400 bg-transparent text-black dark:text-white"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    disabled={processing}
                                    required
                                    autoFocus
                                />
                            </div>
                            <InputError message={errors.email} />

                            {/* Submit Button */}
                            <Button
                                type="submit"
                                className="w-full bg-[#F61501] mt-4 py-2 rounded-2xl text-white font-semibold"
                                disabled={processing}
                            >
                                {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2 inline" />}
                                Email Password Reset Link
                            </Button>

                            {/* Link to Login */}
                            <div className="text-center text-sm mt-2 text-[#F61501]">
                                Or, return to{' '}
                                <TextLink href={route('login')} className="text-[#F61501] hover:underline">
                                    log in
                                </TextLink>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}
