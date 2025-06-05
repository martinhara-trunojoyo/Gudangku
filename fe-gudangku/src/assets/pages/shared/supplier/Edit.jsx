import { useState, useEffect } from "react";
import { useNavigate, useParams, Link } from "react-router-dom";
import { FaArrowLeft } from "react-icons/fa";

export default function SupplierEdit() {
    const navigate = useNavigate();
    const { id } = useParams();
    const [formData, setFormData] = useState({
        nama: "",
        alamat: "",
        kontak: ""
    });

    return (
        <div className="min-h-screen bg-gradient-to-br from-purple-50 via-purple-100 to-blue-100 pt-20 relative overflow-hidden">
            {/* Decorative Background Elements */}
            <div className="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
                {/* Large Circle */}
                <div className="absolute -top-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-gradient-to-br from-purple-200/30 to-purple-300/20 backdrop-blur-3xl"></div>
                
                {/* Small Circles */}
                <div className="absolute top-[20%] left-[5%] w-16 h-16 rounded-full bg-blue-200/20"></div>
                <div className="absolute bottom-[10%] right-[20%] w-24 h-24 rounded-full bg-purple-200/20"></div>
                
                {/* Wave Pattern */}
                <svg className="absolute bottom-0 left-0 w-full opacity-20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="#8B5CF6" fillOpacity="0.5" d="M0,256L48,240C96,224,192,192,288,192C384,192,480,224,576,213.3C672,203,768,149,864,149.3C960,149,1056,203,1152,208C1248,213,1344,171,1392,149.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
                
                {/* Grid Pattern */}
                <div className="absolute inset-0 bg-grid-pattern opacity-5"></div>
            </div>

            {/* Floating Back Button */}
            

            <div className="container mx-auto px-4 py-8 relative z-10">
                <div className="max-w-6xl mx-auto bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden border border-gray-100/50">
                    <div className="flex flex-col lg:flex-row">
                        {/* Left Side - Illustration */}
                        <div className="w-full lg:w-1/2 bg-gradient-to-br from-purple-400 via-purple-500 to-purple-600 p-8 lg:p-12 flex items-center justify-center">
                            <div className="relative w-full max-w-md">
                                <img 
                                    src="/img/illustration/edit-supplier.png" 
                                    alt="Edit Supplier Illustration"
                                    className="w-full"
                                    onError={(e) => {
                                        e.target.style.display = 'none';
                                        e.target.nextSibling.style.display = 'block';
                                    }}
                                />
                                
                                {/* Fallback illustration */}
                                <div className="hidden">
                                    <svg className="w-full h-auto" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        {/* Simple SVG illustration */}
                                        <rect x="120" y="100" width="160" height="120" rx="10" fill="white" stroke="#8B5CF6" strokeWidth="4"/>
                                        <path d="M140 150 L180 190 L260 110" stroke="#8B5CF6" strokeWidth="8" strokeLinecap="round" strokeLinejoin="round"/>
                                    </svg>
                                    <div className="mt-8 text-center">
                                        <h3 className="text-white text-2xl font-bold mb-2">Edit Supplier</h3>
                                        <p className="text-white/80">Update informasi supplier</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Right Side - Form */}
                        <div className="w-full lg:w-1/2 p-8 lg:p-12">
                            <h2 className="text-3xl font-bold text-purple-600 mb-8 text-center">
                                Edit Supplier
                            </h2>
                            
                            <form  className="space-y-6">
                                {/* Form fields - same as Tambah.jsx */}
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Nama Supplier
                                    </label>
                                    <input
                                        type="text"
                                        name="nama"
                                        value={formData.nama}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                        placeholder="Masukkan nama supplier"
                                        required
                                    />
                                </div>

                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Alamat
                                    </label>
                                    <textarea
                                        name="alamat"
                                        value={formData.alamat}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none"
                                        placeholder="Masukkan alamat lengkap"
                                        rows="3"
                                        required
                                    />
                                </div>

                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Kontak
                                    </label>
                                    <input
                                        type="tel"
                                        name="kontak"
                                        value={formData.kontak}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                        placeholder="Masukkan nomor telepon"
                                        required
                                    />
                                </div>

                                {/* Submit and Cancel Buttons */}
                                <div className="pt-6 flex gap-3">
                                    <button
                                        type="submit"
                                        className="w-3/4 bg-purple-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-purple-700 transition duration-300 transform hover:scale-[1.02] shadow-lg"
                                    >
                                        Simpan Perubahan
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
            
            {/* Grid Pattern Styles */}
            <style jsx>{`
                .bg-grid-pattern {
                    background-image: linear-gradient(to right, rgba(107, 33, 168, 0.1) 1px, transparent 1px),
                                    linear-gradient(to bottom, rgba(107, 33, 168, 0.1) 1px, transparent 1px);
                    background-size: 20px 20px;
                }
            `}</style>
        </div>
    );
}
