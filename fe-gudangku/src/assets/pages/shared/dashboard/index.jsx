import { Link } from "react-router-dom";
import { FaBox, FaTruck, FaClipboardList, FaChartBar, FaUsers, FaTags } from "react-icons/fa";

export default function Dashboard() {
    // Get current user data (mock implementation)
    const currentUser = JSON.parse(localStorage.getItem('user') || '{}');
    const userName = currentUser.nama || 'Petugas';

    const menuItems = [
        {
            title: "Supplier",
            icon: <FaUsers className="text-4xl mb-4" />,
            description: "Kelola data supplier",
            link: "/petugas/supplier",
            color: "bg-blue-500"
        },
        {
            title: "Kategori",
            icon: <FaTags className="text-4xl mb-4" />,
            description: "Kelola kategori barang",
            link: "/petugas/kategori",
            color: "bg-green-500"
        },
        {
            title: "Barang",
            icon: <FaBox className="text-4xl mb-4" />,
            description: "Kelola stok barang",
            link: "/petugas/barang",
            color: "bg-purple-500"
        },
        {
            title: "Barang Masuk",
            icon: <FaTruck className="text-4xl mb-4" />,
            description: "Catat barang masuk",
            link: "/petugas/barang/masuk",
            color: "bg-yellow-500"
        },
        {
            title: "Barang Keluar",
            icon: <FaClipboardList className="text-4xl mb-4" />,
            description: "Catat barang keluar",
            link: "/petugas/barang/keluar",
            color: "bg-red-500"
        },
        {
            title: "Laporan",
            icon: <FaChartBar className="text-4xl mb-4" />,
            description: "Lihat laporan",
            link: "/petugas/laporan/masuk",
            color: "bg-indigo-500"
        }
    ];

    return (
        <div className="min-h-screen bg-gradient-to-br from-purple-50 via-purple-100 to-blue-100 pt-20 relative overflow-hidden">
            {/* Decorative Background Elements */}
            <div className="absolute inset-0 z-0 overflow-hidden">
                {/* Large Gradient Circles */}
                <div className="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-gradient-to-br from-purple-300/30 to-blue-300/20 blur-3xl"></div>
                <div className="absolute -bottom-20 -left-20 w-80 h-80 rounded-full bg-gradient-to-tr from-indigo-300/20 to-pink-300/20 blur-3xl"></div>
                
                {/* Floating Geometric Shapes */}
                <div className="absolute top-1/4 left-10 w-24 h-24 bg-blue-200/30 rounded-lg rotate-12 animate-float-slow"></div>
                <div className="absolute bottom-1/3 right-10 w-16 h-16 bg-purple-200/30 rounded-full animate-float-medium"></div>
                <div className="absolute top-2/3 left-1/3 w-12 h-12 bg-indigo-200/30 rounded-lg -rotate-12 animate-float-fast"></div>
                
                {/* Abstract Wave */}
                <svg className="absolute bottom-0 left-0 w-full h-32 opacity-10" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#8B5CF6" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,138.7C672,149,768,203,864,202.7C960,203,1056,149,1152,133.3C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
                
                {/* Dot Grid Pattern */}
                <div className="absolute inset-0 bg-dot-pattern opacity-5"></div>
            </div>

            <div className="container mx-auto px-6 py-8 relative z-10">
                {/* Header with Animation */}
                <div className="mb-10 animate-fade-in">
                    <h1 className="text-3xl font-bold text-gray-800 mb-2 relative">
                        Selamat Datang, {userName}! 👋
                        <span className="absolute -bottom-1 left-0 w-20 h-1 bg-purple-500 rounded"></span>
                    </h1>
                    <p className="text-gray-600">Dashboard Petugas Gudang</p>
                </div>

                {/* Menu Grid with Hover Effects */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-up">
                    {menuItems.map((item, index) => (
                        <Link
                            key={index}
                            to={item.link}
                            className="bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:scale-105 transition-all duration-300 border border-gray-100/50"
                        >
                            <div className={`${item.color} text-white p-4 rounded-lg inline-block mb-4 shadow-md`}>
                                {item.icon}
                            </div>
                            <h3 className="text-xl font-semibold text-gray-800 mb-2">{item.title}</h3>
                            <p className="text-gray-600">{item.description}</p>
                        </Link>
                    ))}
                </div>

                {/* Quick Stats with Glass Effect */}
                <div className="mt-12 grid grid-cols-1 md:grid-cols-4 gap-6 animate-fade-up-delay">
                    <div className="bg-white/80 backdrop-blur-sm rounded-lg shadow p-6 border border-gray-100/50 hover:shadow-md transition-all">
                        <h4 className="text-gray-600 text-sm font-medium">Total Supplier</h4>
                        <p className="text-2xl font-bold text-gray-800 mt-2">24</p>
                        <div className="h-1 w-12 bg-blue-500 mt-2 rounded-full"></div>
                    </div>
                    <div className="bg-white/80 backdrop-blur-sm rounded-lg shadow p-6 border border-gray-100/50 hover:shadow-md transition-all">
                        <h4 className="text-gray-600 text-sm font-medium">Total Barang</h4>
                        <p className="text-2xl font-bold text-gray-800 mt-2">156</p>
                        <div className="h-1 w-12 bg-green-500 mt-2 rounded-full"></div>
                    </div>
                    <div className="bg-white/80 backdrop-blur-sm rounded-lg shadow p-6 border border-gray-100/50 hover:shadow-md transition-all">
                        <h4 className="text-gray-600 text-sm font-medium">Barang Masuk Hari Ini</h4>
                        <p className="text-2xl font-bold text-gray-800 mt-2">12</p>
                        <div className="h-1 w-12 bg-yellow-500 mt-2 rounded-full"></div>
                    </div>
                    <div className="bg-white/80 backdrop-blur-sm rounded-lg shadow p-6 border border-gray-100/50 hover:shadow-md transition-all">
                        <h4 className="text-gray-600 text-sm font-medium">Barang Keluar Hari Ini</h4>
                        <p className="text-2xl font-bold text-gray-800 mt-2">8</p>
                        <div className="h-1 w-12 bg-red-500 mt-2 rounded-full"></div>
                    </div>
                </div>
            </div>
            
            {/* Styles for animations and patterns */}
            <style jsx>{`
                @keyframes float-slow {
                    0%, 100% { transform: translateY(0) rotate(12deg); }
                    50% { transform: translateY(-10px) rotate(12deg); }
                }
                
                @keyframes float-medium {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-15px); }
                }
                
                @keyframes float-fast {
                    0%, 100% { transform: translateY(0) rotate(-12deg); }
                    50% { transform: translateY(-20px) rotate(-12deg); }
                }
                
                @keyframes fade-in {
                    from { opacity: 0; transform: translateY(-10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                
                @keyframes fade-up {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                
                .animate-float-slow {
                    animation: float-slow 7s ease-in-out infinite;
                }
                
                .animate-float-medium {
                    animation: float-medium 5s ease-in-out infinite;
                }
                
                .animate-float-fast {
                    animation: float-fast 4s ease-in-out infinite;
                }
                
                .animate-fade-in {
                    animation: fade-in 0.8s ease-out forwards;
                }
                
                .animate-fade-up {
                    animation: fade-up 1s ease-out forwards;
                }
                
                .animate-fade-up-delay {
                    animation: fade-up 1s ease-out 0.3s forwards;
                    opacity: 0;
                }
                
                .bg-dot-pattern {
                    background-image: radial-gradient(#6B21A8 1px, transparent 1px);
                    background-size: 30px 30px;
                }
            `}</style>
        </div>
    );
}