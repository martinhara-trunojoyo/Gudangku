import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { FaArrowLeft } from "react-icons/fa";

export default function SupplierTambah() {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        nama: "",
        kontak: "",
        alamat: ""
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
        // Navigate back to supplier list after submission
        navigate("/admin/supplier");
    };

    const goBack = () => navigate("/admin/supplier");

    return (
        <div className="min-h-screen bg-gray-50 pt-20 relative overflow-hidden">
            {/* Decorative Background Elements */}
            <div className="absolute inset-0 z-0 overflow-hidden">
                {/* Large Gradient Circles */}
                <div className="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-purple-300/30 to-blue-300/20 rounded-full blur-3xl"></div>
                <div className="absolute -bottom-40 -left-20 w-80 h-80 bg-gradient-to-tr from-indigo-300/20 to-pink-300/20 rounded-full blur-3xl"></div>
                
                {/* Floating Geometric Shapes */}
                <div className="absolute top-1/4 left-10 w-20 h-20 bg-blue-200/30 rounded-lg rotate-12 animate-float-slow"></div>
                <div className="absolute bottom-1/3 right-10 w-16 h-16 bg-purple-200/30 rounded-full animate-float-medium"></div>
            </div>

            {/* Floating Back Button */}
            <div className="absolute top-24 left-6 md:left-10 z-10">
                <button 
                    onClick={goBack}
                    className="group flex items-center gap-2 px-4 py-2 bg-white/80 hover:bg-purple-50 backdrop-blur-sm rounded-lg shadow-md border border-purple-100 transition-all duration-300 hover:shadow-lg"
                >
                    <FaArrowLeft className="text-purple-600 group-hover:-translate-x-1 transition-transform duration-300" />
                    <span className="text-gray-700 font-medium group-hover:text-purple-700 transition-colors duration-300">Kembali</span>
                </button>
            </div>

            <div className="container mx-auto px-4 py-8 relative z-10">
                <div className="max-w-6xl mx-auto bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden border border-gray-100/50">
                    <div className="flex flex-col lg:flex-row">
                        {/* Left Side - Illustration */}
                        <div className="w-full lg:w-1/2 bg-gradient-to-br from-purple-400 via-purple-500 to-purple-600 p-8 lg:p-12 flex items-center justify-center">
                            <div className="relative w-full max-w-md">
                                {/* Illustration image */}
                                <img 
                                    src="/img/illustration/tambah-supplier.png" 
                                    alt="Supplier Illustration"
                                    className="w-full"
                                    onError={(e) => {
                                        e.target.style.display = 'none';
                                        e.target.nextSibling.style.display = 'block';
                                    }}
                                />
                                
                                {/* Fallback illustration if image not found */}
                                <div className="hidden">
                                    <svg className="w-full h-auto" viewBox="0 0 400 350" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        {/* Building/Factory */}
                                        <rect x="100" y="120" width="200" height="180" rx="5" fill="#8B5CF6" fillOpacity="0.8"/>
                                        <rect x="120" y="160" width="50" height="70" rx="5" fill="white"/>
                                        <rect x="190" y="160" width="50" height="70" rx="5" fill="white"/>
                                        <rect x="240" y="210" width="40" height="90" rx="5" fill="white"/>
                                        
                                        {/* Roof */}
                                        <path d="M80,120 L200,70 L320,120 Z" fill="#8B5CF6"/>
                                        
                                        {/* Chimney */}
                                        <rect x="140" y="60" width="30" height="50" fill="#8B5CF6" fillOpacity="0.6"/>
                                        <path d="M140,60 C140,50 170,50 170,60" fill="#8B5CF6"/>
                                        
                                        {/* Truck */}
                                        <rect x="50" y="250" width="80" height="40" rx="5" fill="#F472B6"/>
                                        <rect x="30" y="270" width="30" height="20" rx="2" fill="#F472B6"/>
                                        <circle cx="60" cy="290" r="10" fill="#374151"/>
                                        <circle cx="120" cy="290" r="10" fill="#374151"/>
                                    </svg>
                                    
                                    <div className="mt-8 text-center text-white">
                                        <h3 className="text-2xl font-bold mb-2">Tambah Supplier</h3>
                                        <p className="text-white/80">Kelola supplier dengan mudah</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Right Side - Form */}
                        <div className="w-full lg:w-1/2 p-8 lg:p-12">
                            <h2 className="text-3xl font-bold text-purple-600 mb-8 text-center">
                                Tambah Supplier
                            </h2>
                            
                            <form onSubmit={handleSubmit} className="space-y-6">
                                {/* Nama Supplier */}
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Nama Supplier
                                    </label>
                                    <input
                                        type="text"
                                        name="nama"
                                        value={formData.nama}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                        placeholder="Masukkan nama supplier"
                                        required
                                    />
                                </div>
                            
                                {/* Kontak */}
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Kontak
                                    </label>
                                    <input
                                        type="tel"
                                        name="kontak"
                                        value={formData.kontak}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                        placeholder="Masukkan nomor telepon"
                                        required
                                    />
                                </div>

                                {/* Alamat */}
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Alamat
                                    </label>
                                    <textarea
                                        name="alamat"
                                        value={formData.alamat}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none"
                                        placeholder="Masukkan alamat lengkap"
                                        rows="3"
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
                                        onClick={goBack}
                                        className="w-1/4 bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg hover:bg-gray-300 transition duration-300"
                                    >
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                {/* Breadcrumb Navigation */}
                <div className="mt-6 max-w-6xl mx-auto px-2">
                    <nav className="flex text-gray-500 text-sm">
                        <Link to="/admin" className="hover:text-purple-600 transition-colors">Dashboard</Link>
                        <span className="mx-2">•</span>
                        <Link to="/admin/supplier" className="hover:text-purple-600 transition-colors">Daftar Supplier</Link>
                        <span className="mx-2">•</span>
                        <span className="text-purple-600 font-medium">Tambah Supplier</span>
                    </nav>
                </div>
            </div>
            
            {/* Styles for animations */}
            <style jsx>{`
                @keyframes float-slow {
                    0%, 100% { transform: translateY(0) rotate(12deg); }
                    50% { transform: translateY(-10px) rotate(12deg); }
                }
                
                @keyframes float-medium {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-15px); }
                }
                
                .animate-float-slow {
                    animation: float-slow 7s ease-in-out infinite;
                }
                
                .animate-float-medium {
                    animation: float-medium 5s ease-in-out infinite;
                }
            `}</style>
        </div>
    );
}
