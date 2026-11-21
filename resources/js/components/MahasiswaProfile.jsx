import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';

/**
 * Komponen Profil Mahasiswa & Kelola CV (M2: F-04, F-05, F-06)
 * Sesuai PRD KerjaKita Bagian 4.5 No. 7
 */
export default function MahasiswaProfile({ token }) {
    // API Endpoints
    const API_PROFILE = '/api/mahasiswa/profile';
    const API_CV = '/api/mahasiswa/profile/cv';

    // State Profil (F-04 & F-05)
    const [profile, setProfile] = useState({
        email: '',
        nama_lengkap: '',
        nim: '',
        universitas: '',
        fakultas: '',
        program_studi: '',
        jenjang: '',
        semester: '',
        domisili: '',
        ketersediaan_waktu: '',
        keahlian: '',
        bio: '',
        cv_file_path: null,
        cv_url: null,
    });

    const [skillTags, setSkillTags] = useState([]);
    const [skillInput, setSkillInput] = useState('');
    const [isLoading, setIsLoading] = useState(true);
    const [isSaving, setIsSaving] = useState(false);
    const [isUploadingCv, setIsUploadingCv] = useState(false);
    const [isDeletingCv, setIsDeletingCv] = useState(false);
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [alert, setAlert] = useState(null); // { type: 'success' | 'error', message: string }
    const [validationErrors, setValidationErrors] = useState({});

    const fileInputRef = useRef(null);

    // Setup Axios Instance dengan Bearer Token
    const getApiClient = () => {
        const authToken = token || localStorage.getItem('auth_token') || '';
        return axios.create({
            headers: {
                Authorization: authToken ? `Bearer ${authToken}` : '',
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
    };

    // 1. F-04: Ambil Data Profil
    const fetchProfile = async () => {
        setIsLoading(true);
        try {
            const api = getApiClient();
            const res = await api.get(API_PROFILE);
            if (res.data?.success) {
                const data = res.data.data;
                setProfile(data);
                if (data.keahlian) {
                    setSkillTags(data.keahlian.split(',').map((s) => s.trim()).filter(Boolean));
                }
            }
        } catch (err) {
            console.error('Fetch profile error:', err);
            setAlert({
                type: 'error',
                message: err.response?.data?.message || 'Gagal memuat profil mahasiswa.',
            });
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        fetchProfile();
    }, []);

    // 2. F-05: Update Profil Mahasiswa
    const handleProfileSubmit = async (e) => {
        e.preventDefault();
        setValidationErrors({});
        setIsSaving(true);

        const payload = {
            nama_lengkap: profile.nama_lengkap,
            nim: profile.nim || null,
            universitas: profile.universitas || null,
            fakultas: profile.fakultas || null,
            program_studi: profile.program_studi || null,
            jenjang: profile.jenjang || null,
            semester: profile.semester ? parseInt(profile.semester, 10) : null,
            domisili: profile.domisili || null,
            ketersediaan_waktu: profile.ketersediaan_waktu || null,
            keahlian: skillTags.join(', ') || null,
            bio: profile.bio || null,
        };

        try {
            const api = getApiClient();
            const res = await api.put(API_PROFILE, payload);
            if (res.data?.success) {
                setAlert({ type: 'success', message: 'Profil mahasiswa berhasil diperbarui!' });
                setProfile((prev) => ({ ...prev, ...res.data.data }));
            }
        } catch (err) {
            console.error('Update error:', err);
            if (err.response?.status === 422) {
                setValidationErrors(err.response.data.errors || {});
                setAlert({ type: 'error', message: 'Silakan periksa kembali isian formulir Anda.' });
            } else {
                setAlert({
                    type: 'error',
                    message: err.response?.data?.message || 'Gagal menyimpan profil.',
                });
            }
        } finally {
            setIsSaving(false);
        }
    };

    // 3. F-06: Upload Berkas CV (Wajib PDF, Maks. 2 MB)
    const handleCvUpload = async (e) => {
        const file = e.target.files?.[0];
        if (!file) return;

        // Validasi Sisi Klien
        if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
            setAlert({ type: 'error', message: 'Format file tidak valid. CV wajib berformat PDF (.pdf).' });
            e.target.value = '';
            return;
        }

        const maxBytes = 2 * 1024 * 1024; // 2 MB
        if (file.size > maxBytes) {
            setAlert({ type: 'error', message: 'Ukuran file melebihi 2 MB. Silakan pilih file PDF yang lebih kecil.' });
            e.target.value = '';
            return;
        }

        setIsUploadingCv(true);
        const formData = new FormData();
        formData.append('cv', file);

        try {
            const api = getApiClient();
            const res = await api.post(API_CV, formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            if (res.data?.success) {
                setAlert({ type: 'success', message: 'File CV berhasil diunggah!' });
                setProfile((prev) => ({
                    ...prev,
                    cv_file_path: res.data.data.cv_file_path,
                    cv_url: res.data.data.cv_url,
                }));
            }
        } catch (err) {
            console.error('Upload CV error:', err);
            setAlert({
                type: 'error',
                message: err.response?.data?.message || 'Gagal mengunggah file CV.',
            });
        } finally {
            setIsUploadingCv(false);
            e.target.value = '';
        }
    };

    // 4. F-06: Hapus Berkas CV
    const handleDeleteCv = async () => {
        setIsDeletingCv(true);
        try {
            const api = getApiClient();
            const res = await api.delete(API_CV);
            if (res.data?.success) {
                setAlert({ type: 'success', message: 'File CV berhasil dihapus dari sistem.' });
                setProfile((prev) => ({
                    ...prev,
                    cv_file_path: null,
                    cv_url: null,
                }));
                setShowDeleteModal(false);
            }
        } catch (err) {
            console.error('Delete CV error:', err);
            setAlert({
                type: 'error',
                message: err.response?.data?.message || 'Gagal menghapus file CV.',
            });
        } finally {
            setIsDeletingCv(false);
        }
    };

    // Skill Tag Handlers
    const addSkill = () => {
        const val = skillInput.trim();
        if (!val) return;
        const newItems = val.split(',').map((s) => s.trim()).filter(Boolean);
        setSkillTags((prev) => Array.from(new Set([...prev, ...newItems])));
        setSkillInput('');
    };

    const removeSkill = (index) => {
        setSkillTags((prev) => prev.filter((_, i) => i !== index));
    };

    if (isLoading) {
        return (
            <div className="max-w-7xl mx-auto p-8 animate-pulse grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div className="lg:col-span-8 bg-white h-96 rounded-3xl border border-slate-200"></div>
                <div className="lg:col-span-4 bg-white h-96 rounded-3xl border border-slate-200"></div>
            </div>
        );
    }

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800">
            {/* Header */}
            <div className="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h1 className="text-2xl font-black text-slate-900 tracking-tight">Profil Mahasiswa & Kelola CV</h1>
                    <p className="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Kelola identitas diri, latar belakang pendidikan, keahlian, dan dokumen CV Anda.
                    </p>
                </div>
                <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 w-fit">
                    <span className="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                    Modul M2 (F-04, F-05, F-06)
                </span>
            </div>

            {/* Alert Banner */}
            {alert && (
                <div
                    className={`mb-6 p-4 rounded-2xl text-xs sm:text-sm font-medium border flex items-center justify-between shadow-sm transition ${
                        alert.type === 'success'
                            ? 'bg-green-50 border-green-200 text-green-800'
                            : 'bg-red-50 border-red-200 text-red-800'
                    }`}
                >
                    <p>{alert.message}</p>
                    <button onClick={() => setAlert(null)} className="text-slate-400 hover:text-slate-600 text-base leading-none">
                        &times;
                    </button>
                </div>
            )}

            {/* Split Panel Layout */}
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {/* Panel Kiri: Data Diri & Akademik (F-04 & F-05) */}
                <div className="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                    <div className="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                        <h2 className="text-lg font-bold text-slate-900">Data Pribadi & Akademik</h2>
                        <span className="text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600">F-04 & F-05</span>
                    </div>

                    <form onSubmit={handleProfileSubmit} className="space-y-6">
                        {/* Section 1: Identitas */}
                        <div>
                            <h3 className="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">1. Identitas Mahasiswa</h3>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-xs font-bold text-slate-700 mb-1">
                                        Nama Lengkap <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        required
                                        value={profile.nama_lengkap}
                                        onChange={(e) => setProfile({ ...profile, nama_lengkap: e.target.value })}
                                        className="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                        placeholder="Nama Lengkap"
                                    />
                                    {validationErrors.nama_lengkap && (
                                        <p className="text-[11px] text-red-500 mt-1">{validationErrors.nama_lengkap[0]}</p>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                                    <input
                                        type="email"
                                        readOnly
                                        value={profile.email}
                                        className="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed outline-none"
                                    />
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-slate-700 mb-1">Domisili / Kota</label>
                                    <input
                                        type="text"
                                        value={profile.domisili || ''}
                                        onChange={(e) => setProfile({ ...profile, domisili: e.target.value })}
                                        className="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                        placeholder="Contoh: Bandung"
                                    />
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-slate-700 mb-1">Ketersediaan Waktu</label>
                                    <input
                                        type="text"
                                        value={profile.ketersediaan_waktu || ''}
                                        onChange={(e) => setProfile({ ...profile, ketersediaan_waktu: e.target.value })}
                                        className="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                        placeholder="Contoh: 20 jam/minggu"
                                    />
                                </div>
                            </div>
                        </div>

                        {/* Section 2: Akademik */}
                        <div>
                            <h3 className="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">2. Latar Belakang Akademik</h3>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-xs font-bold text-slate-700 mb-1">NIM</label>
                                    <input
                                        type="text"
                                        value={profile.nim || ''}
                                        onChange={(e) => setProfile({ ...profile, nim: e.target.value })}
                                        className="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                        placeholder="Contoh: 102022400199"
                                    />
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-slate-700 mb-1">Universitas</label>
                                    <input
                                        type="text"
                                        value={profile.universitas || ''}
                                        onChange={(e) => setProfile({ ...profile, universitas: e.target.value })}
                                        className="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                        placeholder="Contoh: Telkom University"
                                    />
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-slate-700 mb-1">Fakultas</label>
                                    <input
                                        type="text"
                                        value={profile.fakultas || ''}
                                        onChange={(e) => setProfile({ ...profile, fakultas: e.target.value })}
                                        className="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                        placeholder="Contoh: Fakultas Informatika"
                                    />
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-slate-700 mb-1">Program Studi</label>
                                    <input
                                        type="text"
                                        value={profile.program_studi || ''}
                                        onChange={(e) => setProfile({ ...profile, program_studi: e.target.value })}
                                        className="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                        placeholder="Contoh: S1 Informatika"
                                    />
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-slate-700 mb-1">Jenjang</label>
                                    <select
                                        value={profile.jenjang || ''}
                                        onChange={(e) => setProfile({ ...profile, jenjang: e.target.value })}
                                        className="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none bg-white"
                                    >
                                        <option value="">-- Pilih Jenjang --</option>
                                        <option value="D3">D3</option>
                                        <option value="D4">D4</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-slate-700 mb-1">Semester</label>
                                    <input
                                        type="number"
                                        min="1"
                                        max="14"
                                        value={profile.semester || ''}
                                        onChange={(e) => setProfile({ ...profile, semester: e.target.value })}
                                        className="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                        placeholder="Contoh: 5"
                                    />
                                </div>
                            </div>
                        </div>

                        {/* Section 3: Keahlian & Bio */}
                        <div>
                            <h3 className="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">3. Keahlian & Bio</h3>
                            <div className="mb-4">
                                <label className="block text-xs font-bold text-slate-700 mb-1">Keahlian (Skill Tags)</label>
                                <div className="flex flex-wrap gap-1.5 mb-2 min-h-[28px]">
                                    {skillTags.map((s, idx) => (
                                        <span key={idx} className="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            {s}
                                            <button type="button" onClick={() => removeSkill(idx)} className="text-blue-400 hover:text-blue-800 font-bold">&times;</button>
                                        </span>
                                    ))}
                                </div>
                                <div className="flex gap-2">
                                    <input
                                        type="text"
                                        value={skillInput}
                                        onChange={(e) => setSkillInput(e.target.value)}
                                        onKeyDown={(e) => {
                                            if (e.key === 'Enter' || e.key === ',') {
                                                e.preventDefault();
                                                addSkill();
                                            }
                                        }}
                                        placeholder="Ketik skill dan tekan Enter atau pisahkan dengan koma"
                                        className="flex-1 px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                    />
                                    <button type="button" onClick={addSkill} className="px-4 py-2 bg-slate-100 hover:bg-slate-200 font-bold text-xs rounded-xl">
                                        Tambah
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label className="block text-xs font-bold text-slate-700 mb-1">Bio Singkat</label>
                                <textarea
                                    rows="3"
                                    value={profile.bio || ''}
                                    onChange={(e) => setProfile({ ...profile, bio: e.target.value })}
                                    placeholder="Tuliskan deskripsi singkat diri dan minat karir Anda..."
                                    className="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                />
                            </div>
                        </div>

                        <div className="pt-4 flex justify-end border-t border-slate-100">
                            <button
                                type="submit"
                                disabled={isSaving}
                                className="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm rounded-xl transition shadow-sm disabled:opacity-50"
                            >
                                {isSaving ? 'Menyimpan...' : 'Simpan Perubahan Profil'}
                            </button>
                        </div>
                    </form>
                </div>

                {/* Panel Kanan: Kelola CV (F-06) */}
                <div className="lg:col-span-4 space-y-6">
                    <div className="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm">
                        <div className="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                            <h2 className="text-base font-bold text-slate-900">Kelola CV (PDF)</h2>
                            <span className="text-[11px] font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600">F-06</span>
                        </div>

                        <input
                            type="file"
                            ref={fileInputRef}
                            accept="application/pdf"
                            onChange={handleCvUpload}
                            className="hidden"
                        />

                        {isUploadingCv ? (
                            <div className="p-8 text-center space-y-2">
                                <div className="inline-block w-8 h-8 border-3 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                                <p className="text-xs font-bold text-slate-800">Mengunggah CV...</p>
                            </div>
                        ) : profile.cv_file_path ? (
                            <div className="space-y-4">
                                <div className="p-4 rounded-2xl bg-red-50 border border-red-100 flex items-start gap-3">
                                    <div className="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        PDF
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <p className="text-xs font-bold text-slate-800 truncate">
                                            {profile.cv_file_path.split('/').pop()}
                                        </p>
                                        <p className="text-[10px] text-emerald-600 font-semibold mt-0.5">CV Aktif & Siap Digunakan</p>
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 gap-2 pt-1">
                                    <a
                                        href={profile.cv_url || `/storage/${profile.cv_file_path}`}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition text-center border border-slate-200"
                                    >
                                        Lihat CV (PDF)
                                    </a>
                                    <button
                                        type="button"
                                        onClick={() => fileInputRef.current?.click()}
                                        className="w-full py-2.5 px-4 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded-xl transition text-center border border-blue-200"
                                    >
                                        Ganti Berkas CV
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => setShowDeleteModal(true)}
                                        className="w-full py-2 px-4 text-red-600 hover:bg-red-50 font-bold text-xs rounded-xl transition text-center"
                                    >
                                        Hapus CV
                                    </button>
                                </div>
                            </div>
                        ) : (
                            <div
                                onClick={() => fileInputRef.current?.click()}
                                className="border-2 border-dashed border-slate-200 hover:border-blue-400 bg-slate-50 hover:bg-blue-50/50 rounded-2xl p-6 text-center cursor-pointer transition"
                            >
                                <div className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center mx-auto mb-3 text-red-500 shadow-sm">
                                    PDF
                                </div>
                                <p className="text-xs font-bold text-slate-800">Unggah Berkas CV</p>
                                <p className="text-[11px] text-slate-500 mt-1">Klik untuk memilih file PDF dari perangkat.</p>
                                <span className="inline-block mt-3 px-3 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-blue-600">
                                    Pilih File PDF
                                </span>
                            </div>
                        )}

                        <div className="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                            <span>Format: <strong>PDF</strong></span>
                            <span>Maksimal: <strong>2 MB</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            {/* Modal Dialog Konfirmasi Hapus CV */}
            {showDeleteModal && (
                <div className="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
                    <div className="bg-white rounded-3xl p-6 max-w-sm w-full shadow-2xl border border-slate-100">
                        <h3 className="text-base font-bold text-slate-900 text-center mb-1">Hapus Berkas CV?</h3>
                        <p className="text-xs text-slate-500 text-center mb-6">
                            Apakah Anda yakin ingin menghapus berkas CV ini dari sistem? Berkas fisik akan dihapus dari server.
                        </p>
                        <div className="flex items-center gap-2">
                            <button
                                type="button"
                                onClick={() => setShowDeleteModal(false)}
                                className="w-1/2 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl"
                            >
                                Batal
                            </button>
                            <button
                                type="button"
                                disabled={isDeletingCv}
                                onClick={handleDeleteCv}
                                className="w-1/2 py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl"
                            >
                                {isDeletingCv ? 'Menghapus...' : 'Ya, Hapus'}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
