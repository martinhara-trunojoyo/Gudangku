import { useState } from "react";
import { Link } from "react-router-dom";
import { FaArrowLeft, FaBox, FaTruck, FaCalendarAlt } from "react-icons/fa";

export default function TambahBarangKeluar() {
    const [formData, setFormData] = useState({
        barang: "",
        tujuan: "",
        jumlah_keluar: "",
        tanggal: new Date().toISOString().split('T')[0]  // Default to today's date
    });

    // Sample data for dropdowns
    const barangOptions = [
        { id: 1, nama: "Minuman Jahe Merah" },
        { id: 2, nama: "Sabun Herbal Alami" },
        { id: 3, nama: "Madu Asli Rasa Bunga" }
    ];

    

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData({
            ...formData,
            [name]: value
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        console.log("Form submitted:", formData);
    };

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

            <div className="container mx-auto px-4 py-8 relative z-10">
                <div className="max-w-6xl mx-auto bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden border border-gray-100/50">
                    <div className="flex flex-col lg:flex-row">
                        {/* Left Side - Illustration */}
                        <div className="w-full lg:w-1/2 bg-gradient-to-br from-purple-400 via-purple-500 to-purple-600 p-8 lg:p-12 flex items-center justify-center">
                            <div className="text-center">
                                <div className="mx-auto mb-8 relative">
                                    {/* Animated illustration for Barang Masuk */}
                                    <div className="w-64 h-64 mx-auto relative">
                                        {/* Boxes animation */}
                                        <div className="absolute top-10 left-10 bg-white/20 w-32 h-32 rounded-lg border-2 border-white/40 flex items-center justify-center transform -rotate-6 animate-pulse">
                                            <FaBox className="text-5xl text-white" />
                                        </div>
                                        <div className="absolute bottom-10 right-10 bg-white/20 w-24 h-24 rounded-lg border-2 border-white/40 flex items-center justify-center transform rotate-12 animate-bounce">
                                            <FaBox className="text-4xl text-white" />
                                        </div>
                                        {/* Arrow pointing in */}
                                        <svg className="absolute top-1/2 left-0 transform -translate-y-1/2" width="80" height="40" viewBox="0 0 80 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0,20 L70,20 M50,5 L70,20 L50,35" stroke="white" strokeWidth="4" strokeLinecap="round" strokeLinejoin="round" />
                                        </svg>
                                        {/* Truck icon */}
                                        <div className="absolute -bottom-4 -left-4 w-20 h-20 bg-white/30 rounded-full flex items-center justify-center">
                                            <FaTruck className="text-3xl text-white" />
                                        </div>
                                    </div>
                                    <h2 className="text-2xl font-bold text-white mt-4 mb-2">Tambah Barang Keluar</h2>
                                    <p className="text-white/80">Catat semua barang yang masuk ke gudang untuk memudahkan pelacakan stok.</p>
                                </div>
                            </div>
                        </div>

                        {/* Right Side - Form */}
                        <div className="w-full lg:w-1/2 p-8 lg:p-12">
                            <h2 className="text-3xl font-bold text-purple-600 mb-8 text-center">
                                Form Barang Keluar
                            </h2>
                            
                            <form onSubmit={handleSubmit} className="space-y-6">
                                {/* Barang (Item) */}
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Nama Barang
                                    </label>
                                    <select
                                        name="barang"
                                        value={formData.barang}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                        required
                                    >
                                        <option value="">Pilih Barang</option>
                                        {barangOptions.map(barang => (
                                            <option key={barang.id} value={barang.id}>
                                                {barang.nama}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                {/* Supplier */}
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Tujuan
                                    </label>
                                    <input
                                        type="text"
                                        name="tujuan"
                                        value={formData.tujuan}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                        placeholder="Masukkan tujuan barang"
                                        required
                                    />
                                   
                                </div>

                                {/* Jumlah keluar */}
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Jumlah Keluar
                                    </label>
                                    <input
                                        type="number"
                                        name="jumlah_keluar"
                                        value={formData.jumlah_keluar}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                        placeholder="Masukkan jumlah barang"
                                        min="1"
                                        required
                                    />
                                </div>

                                {/* Tanggal */}
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Tanggal
                                    </label>
                                    <div className="relative">
                                        <input
                                            type="date"
                                            name="tanggal"
                                            value={formData.tanggal}
                                            onChange={handleChange}
                                            className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                            required
                                        />
                                        <FaCalendarAlt className="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500" />
                                    </div>
                                </div>

                                {/* Submit and Cancel Buttons */}
                                <div className="pt-6 flex gap-3">
                                    <button
                                        type="submit"
                                        className="w-3/4 bg-purple-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-purple-700 transition duration-300 transform hover:scale-[1.02] shadow-lg"
                                    >
                                        Simpan
                                    </button>
                                    <Link
                                        to="#"
                                        className="w-1/4 bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg hover:bg-gray-300 transition duration-300 flex items-center justify-center"
                                    >
                                        Batal
                                    </Link>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                {/* Breadcrumb Navigation */}
                <div className="mt-6 max-w-6xl mx-auto px-2">
                    <nav className="flex text-gray-500 text-sm">
                        <Link to="/" className="hover:text-purple-600 transition-colors">Dashboard</Link>
                        <span className="mx-2">•</span>
                        <Link to="/barang" className="hover:text-purple-600 transition-colors">Barang</Link>
                        <span className="mx-2">•</span>
                        <span className="text-purple-600 font-medium">Tambah Barang keluar</span>
                    </nav>
                </div>
            </div>
            
            {/* Grid Pattern Style */}
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
