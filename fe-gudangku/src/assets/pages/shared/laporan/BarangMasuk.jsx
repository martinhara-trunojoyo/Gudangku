import { useState } from "react";
import { FaSearch, FaFilePdf, FaFileExcel, FaCalendarAlt } from "react-icons/fa";

export default function BarangMasuk() {
    const [searchTerm, setSearchTerm] = useState("");
    const [currentPage, setCurrentPage] = useState(1);
    const [dateRange, setDateRange] = useState({
        startDate: "",
        endDate: ""
    });

    const handleDateRangeChange = (e) => {
        setDateRange({
            ...dateRange,
            [e.target.name]: e.target.value
        });
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

            <div className="container mx-auto px-6 py-8 relative z-10">
                {/* Header */}
                <div className="mb-8">
                    <h1 className="text-2xl font-semibold text-gray-800 mb-2">
                        Laporan Barang Masuk
                    </h1>
                    <p className="text-gray-600">Lihat history barang yang masuk ke inventory</p>
                </div>

                {/* Main Content Section */}
                <div className="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                    <h2 className="text-2xl font-bold text-center mb-8">HISTORY BARANG MASUK</h2>
                    
                    {/* Actions Bar */}
                    <div className="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        {/* Date Range Filter */}
                        <div className="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                            <div className="relative">
                                <input
                                    type="date"
                                    name="startDate"
                                    value={dateRange.startDate}
                                    onChange={handleDateRangeChange}
                                    className="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                />
                                <FaCalendarAlt className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                            </div>
                            <span className="self-center">to</span>
                            <div className="relative">
                                <input
                                    type="date"
                                    name="endDate"
                                    value={dateRange.endDate}
                                    onChange={handleDateRangeChange}
                                    className="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                />
                                <FaCalendarAlt className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                            </div>
                            <button className="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                Filter
                            </button>
                        </div>

                        {/* Export Buttons & Search */}
                        <div className="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                            <button className="flex items-center justify-center gap-1 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                <FaFilePdf />
                                <span>Export PDF</span>
                            </button>
                            <button className="flex items-center justify-center gap-1 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                <FaFileExcel />
                                <span>Export Excel</span>
                            </button>
                            <div className="relative">
                                <input
                                    type="text"
                                    placeholder="Search..."
                                    value={searchTerm}
                                    onChange={(e) => setSearchTerm(e.target.value)}
                                    className="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                />
                                <FaSearch className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                            </div>
                        </div>
                    </div>

                    {/* Table */}
                    <div className="overflow-x-auto">
                        <table className="w-full">
                            <thead>
                                <tr className="bg-[#4A5568] text-white">
                                    <th className="py-3 px-4 text-left">No</th>
                                    <th className="py-3 px-4 text-left">Nama Barang</th>
                                    <th className="py-3 px-4 text-left">Jumlah Masuk</th>
                                    <th className="py-3 px-4 text-left">Tanggal</th>
                                    <th className="py-3 px-4 text-left">Supplier</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr className="border-b hover:bg-gray-50">
                                    <td className="py-3 px-4">1</td>
                                    <td className="py-3 px-4">Minuman Jahe Merah</td>
                                    <td className="py-3 px-4">15</td>
                                    <td className="py-3 px-4">2023-05-12</td>
                                    <td className="py-3 px-4">PT. Sumber Rejeki</td>
                                </tr>
                                <tr className="border-b hover:bg-gray-50">
                                    <td className="py-3 px-4">2</td>
                                    <td className="py-3 px-4">Sabun Herbal Alami</td>
                                    <td className="py-3 px-4">20</td>
                                    <td className="py-3 px-4">2023-05-10</td>
                                    <td className="py-3 px-4">CV. Maju Bersama</td>
                                </tr>
                                <tr className="border-b hover:bg-gray-50">
                                    <td className="py-3 px-4">3</td>
                                    <td className="py-3 px-4">Madu Asli Rasa Bunga</td>
                                    <td className="py-3 px-4">10</td>
                                    <td className="py-3 px-4">2023-05-08</td>
                                    <td className="py-3 px-4">UD. Makmur Jaya</td>
                                </tr>
                                <tr className="border-b hover:bg-gray-50">
                                    <td className="py-3 px-4">4</td>
                                    <td className="py-3 px-4">Minuman Jahe Merah</td>
                                    <td className="py-3 px-4">8</td>
                                    <td className="py-3 px-4">2023-05-05</td>
                                    <td className="py-3 px-4">PT. Sumber Rejeki</td>
                                </tr>
                                <tr className="border-b hover:bg-gray-50">
                                    <td className="py-3 px-4">5</td>
                                    <td className="py-3 px-4">Sabun Herbal Alami</td>
                                    <td className="py-3 px-4">15</td>
                                    <td className="py-3 px-4">2023-05-03</td>
                                    <td className="py-3 px-4">CV. Maju Bersama</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    <div className="mt-6 flex items-center justify-between">
                        <div className="text-sm text-gray-600">
                            Showing 1 to 5 of 5 entries
                        </div>
                        <div className="flex gap-1">
                            <button
                                onClick={() => setCurrentPage(Math.max(1, currentPage - 1))}
                                disabled={currentPage === 1}
                                className="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                &lt;
                            </button>
                            <button
                                onClick={() => setCurrentPage(1)}
                                className="px-3 py-1 rounded bg-purple-500 text-white"
                            >
                                1
                            </button>
                            <button
                                onClick={() => setCurrentPage(Math.min(1, currentPage + 1))}
                                disabled={currentPage === 1}
                                className="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                &gt;
                            </button>
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