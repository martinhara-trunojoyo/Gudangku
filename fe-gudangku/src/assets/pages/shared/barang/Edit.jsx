import { useState } from "react";
import { Link } from "react-router-dom";
import { FaArrowLeft } from "react-icons/fa";

export default function BarangEdit() {
    const [formData, setFormData] = useState({
        nama: "",
        satuan: "",
        stok: "",
        batasMinimum: ""
    });

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        // Handle form submission
        console.log("Form data:", formData);
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
                                <div className="w-64 h-64 mx-auto mb-8 relative">
                                    <div className="absolute inset-0 bg-white/10 rounded-full"></div>
                                    <div className="absolute top-8 left-8 w-48 h-48 bg-white/20 rounded-full flex items-center justify-center">
                                        <div className="w-32 h-32 bg-white/30 rounded-full flex items-center justify-center">
                                            <div className="text-white text-6xl">📦</div>
                                        </div>
                                    </div>
                                </div>
                                <h2 className="text-2xl font-bold text-white mb-4">Edit Barang</h2>
                                <p className="text-purple-100">Kelola inventory dengan mudah dan efisien</p>
                            </div>
                        </div>

                        {/* Right Side - Form */}
                        <div className="w-full lg:w-1/2 p-8 lg:p-12">
                            <h2 className="text-3xl font-bold text-purple-600 mb-8 text-center">
                                Form Barang
                            </h2>
                            
                            <form onSubmit={handleSubmit} className="space-y-6">
                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Nama Barang
                                    </label>
                                    <input
                                        type="text"
                                        name="nama"
                                        value={formData.nama}
                                        onChange={handleInputChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="Masukkan nama barang"
                                        required
                                    />
                                </div>

                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Satuan
                                    </label>
                                    <select
                                        name="satuan"
                                        value={formData.satuan}
                                        onChange={handleInputChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        required
                                    >
                                        <option value="">Pilih satuan</option>
                                        <option value="Pcs">Pcs</option>
                                        <option value="Kg">Kg</option>
                                        <option value="Gram">Gram</option>
                                        <option value="Liter">Liter</option>
                                        <option value="Meter">Meter</option>
                                        <option value="Box">Box</option>
                                        <option value="Pack">Pack</option>
                                        <option value="Unit">Unit</option>
                                    </select>
                                </div>

                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Stok
                                    </label>
                                    <input
                                        type="number"
                                        name="stok"
                                        value={formData.stok}
                                        onChange={handleInputChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="Masukkan jumlah stok"
                                        min="0"
                                        required
                                    />
                                </div>

                                <div>
                                    <label className="block text-gray-600 text-sm mb-2">
                                        Batas Minimum
                                    </label>
                                    <input
                                        type="number"
                                        name="batasMinimum"
                                        value={formData.batasMinimum}
                                        onChange={handleInputChange}
                                        className="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="Masukkan batas minimum stok"
                                        min="0"
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
