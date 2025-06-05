import { useState } from "react";
import { useNavigate } from "react-router-dom";

export default function PetugasTambah() {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        nama: "",
        username: "",
        email: "",
        password: ""
    });

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        // Handle form submission here
        console.log("Form submitted:", formData);
        // Navigate back to petugas list after submission
        navigate("/admin/petugas");
    };

    return (
        <div className="min-h-screen bg-gray-50 pt-20">
            <div className="container mx-auto px-4 py-8">
                <div className="max-w-6xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div className="flex flex-col lg:flex-row">
                        {/* Left Side - Illustration */}
                        <div className="w-full lg:w-1/2 bg-gradient-to-br from-purple-400 via-purple-500 to-purple-600 p-8 lg:p-12 flex items-center justify-center">
                            <div className="relative w-full max-w-md">
                                {/* Illustration image */}
                                <img 
                                    src="/img/illustration/tambah-petugas.png" 
                                    alt="Petugas Illustration"
                                    className="w-full"
                                    onError={(e) => {
                                        e.target.style.display = 'none';
                                        e.target.nextSibling.style.display = 'block';
                                    }}
                                />
                                
                                {/* Fallback illustration if image not found */}
                                <div className="hidden">
                                    <svg className="w-full h-auto" viewBox="0 0 400 350" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        {/* Person 1 */}
                                        <circle cx="120" cy="120" r="30" fill="#FFD93D"/>
                                        <rect x="90" y="150" width="60" height="80" rx="10" fill="#74B9FF"/>
                                        <rect x="85" y="230" width="25" height="60" rx="5" fill="#2D3436"/>
                                        <rect x="130" y="230" width="25" height="60" rx="5" fill="#2D3436"/>
                                        
                                        {/* Person 2 */}
                                        <circle cx="280" cy="130" r="30" fill="#FFD93D"/>
                                        <rect x="250" y="160" width="60" height="70" rx="10" fill="#FD79A8"/>
                                        <rect x="245" y="230" width="25" height="60" rx="5" fill="#2D3436"/>
                                        <rect x="290" y="230" width="25" height="60" rx="5" fill="#2D3436"/>
                                        
                                        {/* Boxes/Packages */}
                                        <rect x="170" y="200" width="60" height="60" rx="5" fill="rgba(255,255,255,0.3)"/>
                                        <rect x="180" y="170" width="40" height="40" rx="5" fill="rgba(255,255,255,0.4)"/>
                                        <rect x="190" y="140" width="30" height="30" rx="5" fill="rgba(255,255,255,0.5)"/>
                                        
                                        {/* Checklist */}
                                        <rect x="270" y="140" width="15" height="15" rx="2" fill="#00B894" stroke="white" strokeWidth="2"/>
                                        <path d="M273 147 L276 150 L282 144" stroke="white" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                        
                                        {/* Ground */}
                                        <rect x="50" y="290" width="300" height="2" fill="rgba(255,255,255,0.3)"/>
                                    </svg>
                                    
                                    <div className="mt-8 text-center text-white">
                                        <h3 className="text-2xl font-bold mb-2">Tambah Petugas</h3>
                                        <p className="text-white/80">Kelola petugas gudang dengan mudah</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Right Side - Form */}
                        <div className="w-full lg:w-1/2 p-8 lg:p-12">
                            <h2 className="text-3xl font-bold text-purple-600 mb-8 text-center">
                                Tambah Petugas
                            </h2>
                            
                            <form onSubmit={handleSubmit} className="space-y-6">
                                {/* Nama */}
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Username
                                    </label>
                                    <input
                                        type="text"
                                        name="nama"
                                        value={formData.nama}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                        placeholder="Masukkan nama lengkap"
                                        required
                                    />
                                </div>
                            
                                {/* Email */}
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Email
                                    </label>
                                    <input
                                        type="email"
                                        name="email"
                                        value={formData.email}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                        placeholder="Masukkan email"
                                        required
                                    />
                                </div>

                                {/* Password */}
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Password
                                    </label>
                                    <input
                                        type="password"
                                        name="password"
                                        value={formData.password}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                        placeholder="Masukkan password"
                                        required
                                    />
                                </div>
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Password (Confirm)
                                    </label>
                                    <input
                                        type="password"
                                        name="password"
                                        value={formData.password}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                        placeholder="Masukkan password"
                                        required
                                    />
                                </div>

                                {/* Submit Button */}
                                <div className="pt-6 flex gap-3">
                                    <button
                                        type="submit"
                                        className="w-3/4 bg-purple-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-purple-700 transition duration-300 transform hover:scale-[1.02] shadow-lg"
                                    >
                                        Tambah
                                    </button>
                                    <button
                                        type="button"
                                        
                                        className="w-1/4 bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg hover:bg-gray-300 transition duration-300"
                                    >
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
} 