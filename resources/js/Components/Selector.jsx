import React from 'react';

const Selector = ({ items, onChange, selectedId }) => {
  return (
    <select className="w-full p-2 border rounded" 
    name="category_id"
    placeholder="category" value={selectedId} onChange={onChange}>
      <option value="">Selecione uma opção</option>
      {items.map(item => (
        <option key={item.id} value={item.id}>
          {item.name}
        </option>
      ))}
    </select>
  );
};

export default Selector;