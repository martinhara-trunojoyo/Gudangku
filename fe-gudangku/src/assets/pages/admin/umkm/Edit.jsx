import { useState } from "react";
import { useNavigate } from "react-router-dom";

export default function EditUMKM() {
     const navigate = useNavigate();
    const [formData, setFormData] = useState({
        namaUmkm: "",
        namaPemilik: "",
        alamat: "",
        kontak: ""
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
        // Redirect to admin dashboard after submission
        navigate("/admin");
    };

    return (
        <div className="min-h-screen relative overflow-hidden pt-20 bg-gradient-to-br from-indigo-50 via-purple-50 to-blue-50">
            {/* Decorative Background Elements */}
            <div className="absolute inset-0 z-0">
                {/* Animated Shapes */}
                <div className="absolute -top-20 -right-20 w-80 h-80 bg-gradient-to-br from-purple-300/20 to-indigo-300/20 rounded-full blur-3xl animate-pulse-slow"></div>
                <div className="absolute -bottom-40 -left-20 w-96 h-96 bg-gradient-to-tr from-violet-300/20 to-blue-300/20 rounded-full blur-3xl animate-pulse-slower"></div>
                
            
                
                {/* Wave Bottom */}
                <svg className="absolute bottom-0 left-0 w-full" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#7C3AED" fillOpacity="0.08" d="M0,96L48,117.3C96,139,192,181,288,186.7C384,192,480,160,576,160C672,160,768,192,864,197.3C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
                
               
            </div>

            <div className="container mx-auto px-4 py-8 relative z-10">
                <div className="max-w-6xl mx-auto bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden border border-purple-100/50">
                    <div className="flex flex-col lg:flex-row">
                        {/* Left Side - Form */}
                        <div className="w-full lg:w-1/2 p-8 lg:p-12">
                            <h1 className="text-3xl font-bold text-violet-600 mb-8">
                                Perbarui Informasi UMKM
                            </h1>
                            
                            <form onSubmit={handleSubmit} className="space-y-6">
                                {/* Nama UMKM */}
                                <div>
                                    <label className="block text-gray-700 text-sm font-medium mb-2">
                                        Nama UMKM
                                    </label>
                                    <input
                                        type="text"
                                        name="namaUmkm"
                                        value={formData.namaUmkm}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition"
                                        placeholder="Masukkan nama UMKM"
                                        required
                                    />
                                </div>

                                {/* Nama Pemilik */}
                                <div>
                                    <label className="block text-gray-700 text-sm font-medium mb-2">
                                        Nama Pemilik
                                    </label>
                                    <input
                                        type="text"
                                        name="namaPemilik"
                                        value={formData.namaPemilik}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition"
                                        placeholder="Masukkan nama pemilik"
                                        required
                                    />
                                </div>

                                {/* Alamat */}
                                <div>
                                    <label className="block text-gray-700 text-sm font-medium mb-2">
                                        Alamat
                                    </label>
                                    <textarea
                                        name="alamat"
                                        value={formData.alamat}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition resize-none"
                                        placeholder="Masukkan alamat lengkap"
                                        rows="3"
                                        required
                                    />
                                </div>

                                {/* Kontak */}
                                <div>
                                    <label className="block text-gray-700 text-sm font-medium mb-2">
                                        Kontak
                                    </label>
                                    <input
                                        type="tel"
                                        name="kontak"
                                        value={formData.kontak}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition"
                                        placeholder="Masukkan nomor telepon"
                                        required
                                    />
                                </div>

                                {/* Submit Button */}
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

                        {/* Right Side - Illustration */}
                        <div className="w-full lg:w-1/2 bg-gradient-to-br from-violet-100/70 to-blue-100/70 backdrop-blur-sm p-8 lg:p-12 flex items-center justify-center">
                            <div className="relative">
                                {/* You can replace this with an actual illustration image */}
                                <img 
                                    src="/img/assets/regis-umkm.png" 
                                    alt="UMKM Registration Illustration"
                                    className="w-full max-w-md"
                                    onError={(e) => {
                                        e.target.style.display = 'none';
                                        e.target.nextSibling.style.display = 'block';
                                    }}
                                />
                                
                                {/* Fallback illustration if image not found */}
                                <div className="hidden">
                                    <div className="w-80 h-80 relative">
                                        {/* Simple SVG illustration as fallback */}
                                        <svg className="w-full h-full" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="100" y="150" width="200" height="150" rx="10" fill="#7C3AED" opacity="0.8"/>
                                            <rect x="120" y="170" width="160" height="110" rx="5" fill="white"/>
                                            <circle cx="150" cy="200" r="15" fill="#7C3AED"/>
                                            <circle cx="250" cy="200" r="15" fill="#7C3AED"/>
                                            <rect x="140" y="230" width="120" height="30" rx="15" fill="#7C3AED" opacity="0.3"/>
                                            <circle cx="200" cy="100" r="30" fill="#7C3AED"/>
                                            <path d="M170 100 Q200 80 230 100" stroke="#7C3AED" strokeWidth="3" fill="none"/>
                                        </svg>
                                        <div className="absolute -bottom-4 -right-4 w-20 h-20 bg-blue-500 rounded-full opacity-20 animate-pulse"></div>
                                        <div className="absolute -top-4 -left-4 w-16 h-16 bg-violet-500 rounded-full opacity-20 animate-bounce"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {/* Custom animations */}
            <style jsx>{`
                .bg-stripes {
                    background-image: linear-gradient(45deg, #7C3AED 25%, transparent 25%, transparent 50%, #7C3AED 50%, #7C3AED 75%, transparent 75%, transparent);
                    background-size: 20px 20px;
                }
                
                @keyframes pulse-slow {
                    0%, 100% { opacity: 0.4; }
                    50% { opacity: 0.7; }
                }
                
                @keyframes pulse-slower {
                    0%, 100% { opacity: 0.3; }
                    50% { opacity: 0.6; }
                }
                
                @keyframes float {
                    0%, 100% { transform: translateY(0) rotate(12deg); }
                    50% { transform: translateY(-15px) rotate(12deg); }
                }
                
                @keyframes float-reverse {
                    0%, 100% { transform: translateY(0) rotate(-12deg); }
                    50% { transform: translateY(-20px) rotate(-12deg); }
                }
                
                @keyframes bounce-slow {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-15px); }
                }
                
                .animate-pulse-slow {
                    animation: pulse-slow 8s infinite;
                }
                
                .animate-pulse-slower {
                    animation: pulse-slower 12s infinite;
                }
                
                .animate-float {
                    animation: float 7s ease-in-out infinite;
                }
                
                .animate-float-reverse {
                    animation: float-reverse 9s ease-in-out infinite;
                }
                
                .animate-bounce-slow {
                    animation: bounce-slow 6s ease-in-out infinite;
                }
            `}</style>
        </div>
    );
}