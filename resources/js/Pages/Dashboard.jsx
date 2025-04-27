import { Link, router } from '@inertiajs/react';

export default function Dashboard({ children }) {
  return (
    <div className="min-h-screen flex bg-gray-100">
      <aside className="w-64 bg-white shadow-md p-4">
        <h2 className="text-xl font-bold mb-6">Dashboard</h2>
        <nav className="space-y-2">
          <Link
            href="/dashboard/stocks"
            className="block px-4 py-2 rounded hover:bg-blue-100 text-gray-700"
          >
            📦 Stocks
          </Link>
          <Link
            href="/dashboard/categories"
            className="block px-4 py-2 rounded hover:bg-blue-100 text-gray-700"
          >
            🗂️ Categories
          </Link>
          <Link
            href="/dashboard/users"
            className="block px-4 py-2 rounded hover:bg-blue-100 text-gray-700"
          >
            👥 Users
          </Link>
        </nav>
      </aside>

      <main className="flex-1 p-6">
        {children || <h1 className="text-2xl font-semibold">Welcome to your dashboard</h1>}
      </main>
    </div>
  );
}
