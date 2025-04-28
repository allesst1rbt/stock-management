import { useEffect, useState } from 'react';
import Dashboard from './Dashboard';

export default function Stocks() {
  const [products, setProducts] = useState({ data: [], meta: null });
  const [filters, setFilters] = useState({
    name: '',
    price: '',
    category: '',
    page: 1,
  });

  const [loading, setLoading] = useState(false);
  const [showModal, setShowModal] = useState(false);
  const [form, setForm] = useState({
    id: null,
    name: '',
    price: '',
    category: '',
    stock_quantity: '',
    sku: '',
    description: '',
  });

  const token = sessionStorage.getItem('token');

  const fetchProducts = async () => {
    setLoading(true);
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
      console.error('Failed to fetch products');
    }

    setLoading(false);
  };

  useEffect(() => {
    fetchProducts();
  }, [filters]);

  const handleFilterChange = (e) => {
    const { name, value } = e.target;
    setFilters((prev) => ({ ...prev, [name]: value, page: 1 }));
  };

  const handleFormChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handleCreate = () => {
    setForm({
      id: null,
      name: '',
      price: '',
      category: '',
      stock_quantity: '',
      sku: '',
      description: '',
    });
    setShowModal(true);
  };

  const handleEdit = (product) => {
    setForm(product);
    setShowModal(true);
  };

  const handleDelete = async (id) => {
    if (!confirm('Are you sure you want to delete this product?')) return;

    const response = await fetch(`/api/v1/products/${id}`, {
      method: 'DELETE',
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    if (response.ok) {
      fetchProducts();
    } else {
      console.error('Failed to delete product');
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const method = form.id ? 'PUT' : 'POST';
    const url = form.id
      ? `/api/v1/products/${form.id}`
      : '/api/v1/products';

    const response = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify(form),
    });

    if (response.ok) {
      setShowModal(false);
      fetchProducts();
    } else {
      console.error('Failed to save product');
    }
  };

  return (
    <Dashboard>
      <div className="mb-4">
        <h1 className="text-2xl font-bold mb-4">Product List</h1>

        <div className="grid grid-cols-4 gap-4 mb-4">
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
          <button
            onClick={handleCreate}
            className="bg-blue-500 text-white rounded px-4 py-2"
          >
            + New Product
          </button>
        </div>
      </div>

      {loading ? (
        <p>Loading...</p>
      ) : (
        <>
          <div className="overflow-x-auto">
            <table className="min-w-full bg-white border rounded">
              <thead className="bg-gray-200">
                <tr>
                  <th className="px-4 py-2 text-left">Name</th>
                  <th className="px-4 py-2 text-left">Price</th>
                  <th className="px-4 py-2 text-left">Category</th>
                  <th className="px-4 py-2 text-left">Stock</th>
                  <th className="px-4 py-2 text-left">SKU</th>
                  <th className="px-4 py-2 text-left">Description</th>
                  <th className="px-4 py-2 text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                {products.data.length > 0 ? (
                  products.data.map((product) => (
                    <tr key={product.id} className="border-t">
                      <td className="px-4 py-2">{product.name}</td>
                      <td className="px-4 py-2">${product.price}</td>
                      <td className="px-4 py-2">
                        {product.category?.name || '—'}
                      </td>
                      <td className="px-4 py-2">{product.stock_quantity}</td>
                      <td className="px-4 py-2">{product.sku}</td>
                      <td className="px-4 py-2">{product.description || '—'}</td>
                      <td className="px-4 py-2 space-x-2">
                        <button
                          onClick={() => handleEdit(product)}
                          className="text-blue-500"
                        >
                          Edit
                        </button>
                        <button
                          onClick={() => handleDelete(product.id)}
                          className="text-red-500"
                        >
                          Delete
                        </button>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td
                      colSpan="7"
                      className="text-center px-4 py-4 text-gray-500"
                    >
                      No products found.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>

          {products.meta && (
            <div className="flex justify-center mt-4 space-x-2">
              {products.meta.links.map((link, index) => (
                <button
                  key={index}
                  disabled={!link.url}
                  onClick={() => {
                    const url = new URL(link.url);
                    const page = url.searchParams.get('page');
                    setFilters((prev) => ({
                      ...prev,
                      page: parseInt(page),
                    }));
                  }}
                  className={`px-3 py-1 rounded ${
                    link.active ? 'bg-blue-500 text-white' : 'bg-gray-200'
                  }`}
                  dangerouslySetInnerHTML={{ __html: link.label }}
                />
              ))}
            </div>
          )}
        </>
      )}

      {showModal && (
        <div className="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
          <div className="bg-white p-6 rounded w-full max-w-md shadow">
            <h2 className="text-xl font-bold mb-4">
              {form.id ? 'Edit Product' : 'New Product'}
            </h2>
            <form onSubmit={handleSubmit} className="space-y-4">
              <input
                type="text"
                name="name"
                placeholder="Name"
                value={form.name}
                onChange={handleFormChange}
                className="w-full p-2 border rounded"
                required
              />
              <input
                type="number"
                name="price"
                placeholder="Price"
                value={form.price}
                onChange={handleFormChange}
                className="w-full p-2 border rounded"
                required
              />
              <input
                type="text"
                name="category"
                placeholder="Category"
                value={form.category}
                onChange={handleFormChange}
                className="w-full p-2 border rounded"
              />
              <input
                type="number"
                name="stock_quantity"
                placeholder="Stock Quantity"
                value={form.stock_quantity}
                onChange={handleFormChange}
                className="w-full p-2 border rounded"
              />
              <input
                type="text"
                name="sku"
                placeholder="SKU"
                value={form.sku}
                onChange={handleFormChange}
                className="w-full p-2 border rounded"
              />
              <textarea
                name="description"
                placeholder="Description"
                value={form.description}
                onChange={handleFormChange}
                className="w-full p-2 border rounded h-24"
              />
              <div className="flex justify-end gap-2">
                <button
                  type="button"
                  onClick={() => setShowModal(false)}
                  className="bg-gray-300 px-4 py-2 rounded"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="bg-blue-500 text-white px-4 py-2 rounded"
                >
                  {form.id ? 'Update' : 'Create'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </Dashboard>
  );
}
