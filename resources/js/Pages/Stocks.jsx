import { useEffect, useState } from 'react';
import Dashboard from './Dashboard';

export default function Stocks() {
  const [products, setProducts] = useState([]);
  const [filters, setFilters] = useState({
    name: '',
    price: '',
    category: '',
  });

  const [loading, setLoading] = useState(false);

  const fetchProducts = async () => {
    setLoading(true);
    const token = sessionStorage.getItem('token');

    const query = new URLSearchParams(filters).toString();
    const response = await fetch(`/api/v1/products?${query}`, {
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${token}`,
      },
    });

    if (response.ok) {
      const data = await response.json();
      setProducts(data);
    } else {
      // handle unauthorized or errors
      console.error('Failed to fetch products');
    }

    setLoading(false);
  };

  useEffect(() => {
    fetchProducts();
  }, [filters]);

  const handleFilterChange = (e) => {
    const { name, value } = e.target;
    setFilters({ ...filters, [name]: value });
  };

  return (
    <Dashboard>
      <div className="mb-4">
        <h1 className="text-2xl font-bold mb-4">Product List</h1>

        <div className="grid grid-cols-3 gap-4 mb-4">
          <input
            type="text"
            name="name"
            placeholder="Filter by name"
            value={filters.name}
            onChange={handleFilterChange}
            className="p-2 border rounded"
          />
          <input
            type="number"
            name="price"
            placeholder="Filter by price"
            value={filters.price}
            onChange={handleFilterChange}
            className="p-2 border rounded"
          />
          <input
            type="text"
            name="category"
            placeholder="Filter by category"
            value={filters.category}
            onChange={handleFilterChange}
            className="p-2 border rounded"
          />
        </div>
      </div>

      {loading ? (
        <p>Loading...</p>
      ) : (
        <div className="overflow-x-auto">
          <table className="min-w-full bg-white border rounded">
            <thead className="bg-gray-200">
              <tr>
                <th className="px-4 py-2 text-left">Name</th>
                <th className="px-4 py-2 text-left">Price</th>
                <th className="px-4 py-2 text-left">Category</th>
                <th className="px-4 py-2 text-left">Stock</th>
                <th className="px-4 py-2 text-left">SKU</th>
              </tr>
            </thead>
            <tbody>
            {Array.isArray(products) && products.length > 0 ? (
                products.map((product) => (
                  <tr key={product.id} className="border-t">
                    <td className="px-4 py-2">{product.name}</td>
                    <td className="px-4 py-2">${product.price}</td>
                    <td className="px-4 py-2">{product.category?.name || '—'}</td>
                    <td className="px-4 py-2">{product.stock_quantity}</td>
                    <td className="px-4 py-2">{product.sku}</td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan="5" className="text-center px-4 py-4 text-gray-500">
                    No products found.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      )}
    </Dashboard>
  );
}
