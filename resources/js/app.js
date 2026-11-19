import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import MahasiswaProfile from './components/MahasiswaProfile';

export { MahasiswaProfile };

const container = document.getElementById('mahasiswa-profile-root');
if (container) {
    const token = container.dataset.token || '';
    const root = createRoot(container);
    root.render(<MahasiswaProfile token={token} />);
}