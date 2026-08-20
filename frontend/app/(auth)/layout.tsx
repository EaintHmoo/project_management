export default function AuthLayout({ children }: { children: React.ReactNode }) {
  return (
    <main className="flex min-h-screen items-center justify-center bg-[#f7f5ef] px-4 py-12">
      <div className="w-full max-w-md">
        <div className="mb-8 text-center">
          <p className="text-xs font-semibold uppercase tracking-[0.18em] text-[#66746e]">Nexus</p>
          <h1 className="mt-2 text-2xl font-bold text-[#18201f]">Collaboration</h1>
        </div>
        <div className="rounded-lg border border-[#ddd4c3] bg-white p-6 shadow-sm">{children}</div>
      </div>
    </main>
  );
}
