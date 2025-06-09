import { useState, useEffect } from "react";
import { Link, useNavigate, useLocation } from "react-router-dom";
import { FaEdit, FaTrash, FaPlus, FaSearch } from "react-icons/fa";
import { getKategori, deleteKategori } from "../../../_service/kategori";
import Swal from "sweetalert2";

export default function KategoriIndex() {
  const [searchTerm, setSearchTerm] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const [kategoriList, setKategoriList] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState("");
  const [userName, setUserName] = useState("User");

  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    const checkAuthAndLoadData = async () => {
      try {
        const token = localStorage.getItem("token");
        const userData = JSON.parse(localStorage.getItem("user") || "{}");

        if (!token || !userData || !userData.role) {
          navigate("/login");
          return;
        }

        if (userData.name) setUserName(userData.name);

        // Kalau ada kategori yang diupdate lewat location.state, update state langsung tanpa load ulang API
        if (location.state?.updatedKategori) {
          setKategoriList((prev) => {
            // Ganti data kategori yang diupdate
            const updated = location.state.updatedKategori;
            const exists = prev.find((k) => k.kategori_id === updated.kategori_id);
            if (exists) {
              return prev.map((k) =>
                k.kategori_id === updated.kategori_id ? updated : k
              );
            } else {
              // Kalau belum ada (misal baru ditambah), tambahkan ke list
              return [updated, ...prev];
            }
          });
          // Hapus state agar tidak dipakai lagi saat re-render
          window.history.replaceState({}, document.title);
          setIsLoading(false);
        } else {
          await loadKategoriData();
        }
      } catch (err) {
        console.error("Error checking auth:", err);
        navigate("/login");
      }
    };

    checkAuthAndLoadData();
  }, [navigate, location]);

  const loadKategoriData = async () => {
    try {
      setIsLoading(true);
      setError("");

      const token = localStorage.getItem("token");
      if (!token) {
        navigate("/login");
        return;
      }

      const data = await getKategori();
      setKategoriList(data || []);
    } catch (err) {
      console.error("Error loading kategori data:", err);
      if (err.message === "Unauthenticated." || err.status === 401) {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        navigate("/login");
        return;
      }
      setError("Failed to load kategori data. Please try again.");
    } finally {
      setIsLoading(false);
    }
  };

  const handleEdit = (id) => {
    navigate(`edit/${id}`);
  };

  const handleDelete = async (id) => {
    const result = await Swal.fire({
      title: "Konfirmasi Hapus",
      text: "Apakah Anda yakin ingin menghapus kategori ini?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc2626",
      cancelButtonColor: "#6b7280",
      confirmButtonText: "Ya, Hapus!",
      cancelButtonText: "Batal",
    });

    if (result.isConfirmed) {
      try {
        await deleteKategori(id);
        await Swal.fire({
          icon: "success",
          title: "Berhasil!",
          text: "Kategori berhasil dihapus.",
          confirmButtonColor: "#7C3AED",
        });
        await loadKategoriData();
      } catch (err) {
        console.error("Error deleting kategori:", err);
        if (err.message === "Unauthenticated." || err.status === 401) {
          localStorage.removeItem("token");
          localStorage.removeItem("user");
          navigate("/login");
          return;
        }
        await Swal.fire({
          icon: "error",
          title: "Gagal!",
          text: "Gagal menghapus kategori. Silakan coba lagi.",
          confirmButtonColor: "#7C3AED",
        });
      }
    }
  };

  const filteredKategori = kategoriList.filter(
    (kategori) =>
      kategori.nama_kategori?.toLowerCase().includes(searchTerm.toLowerCase()) ||
      kategori.deskripsi_kategori?.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const itemsPerPage = 10;
  const totalPages = Math.ceil(filteredKategori.length / itemsPerPage);
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  const currentKategori = filteredKategori.slice(startIndex, endIndex);

  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-purple-600"></div>
          <p className="mt-4 text-gray-600">Loading kategori data...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-purple-50 via-purple-100 to-blue-100 pt-20 relative overflow-hidden">
      <div className="container mx-auto px-6 py-8 relative z-10">
        <div className="mb-8">
          <h1 className="text-2xl font-semibold text-gray-800 mb-2">
            Hello, {userName}! 👋
          </h1>
        </div>

        <div className="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6">
          <h2 className="text-2xl font-bold text-center mb-8">KATEGORI</h2>

          {error && (
            <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
              {error}
            </div>
          )}

          <div className="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <Link
              to="tambah"
              className="flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition"
            >
              <FaPlus className="text-sm" />
              <span>Add Data Kategori</span>
            </Link>

            <div className="relative w-full md:w-auto">
              <input
                type="text"
                placeholder="Search Kategori"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
              />
              <FaSearch className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
            </div>
          </div>

          <div className="overflow-x-auto">
            <table className="min-w-full">
              <thead>
                <tr className="bg-[#4A5568] text-white">
                  <th className="py-3 px-4 text-left">No</th>
                  <th className="py-3 px-4 text-left">Nama Kategori</th>
                  <th className="py-3 px-4 text-left">Deskripsi</th>
                  <th className="py-3 px-4 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                {currentKategori.length > 0 ? (
                  currentKategori.map((kategori, idx) => (
                    <tr key={kategori.kategori_id} className="border-b hover:bg-gray-50">
                      <td className="py-3 px-4">{startIndex + idx + 1}</td>
                      <td className="py-3 px-4">{kategori.nama_kategori || "-"}</td>
                      <td className="py-3 px-4 whitespace-pre-wrap">
                        {kategori.deskripsi || "-"}
                      </td>
                      <td className="py-3 px-4">
                        <div className="flex justify-center gap-2">
                          <button
                            onClick={() => handleEdit(kategori.kategori_id)}
                            className="flex items-center gap-1 bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 transition"
                          >
                            <FaEdit className="text-sm" />
                            <span className="text-sm">Edit</span>
                          </button>
                          <button
                            onClick={() => handleDelete(kategori.kategori_id)}
                            className="flex items-center gap-1 bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition"
                          >
                            <FaTrash className="text-sm" />
                            <span className="text-sm">Hapus</span>
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan="4" className="py-8 text-center text-gray-500">
                      {filteredKategori.length === 0 && searchTerm
                        ? "Tidak ada kategori yang ditemukan"
                        : "Belum ada data kategori"}
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>

          {filteredKategori.length > 0 && (
            <div className="mt-6 flex items-center justify-between">
              <div className="text-sm text-gray-600">
                Showing {startIndex + 1} to {Math.min(endIndex, filteredKategori.length)} of {filteredKategori.length} entries
              </div>
              <div className="flex gap-1">
                <button
                  onClick={() => setCurrentPage(Math.max(1, currentPage - 1))}
                  disabled={currentPage === 1}
                  className="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  &lt;
                </button>
                {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
                  <button
                    key={page}
                    onClick={() => setCurrentPage(page)}
                    className={`px-3 py-1 rounded ${
                      currentPage === page
                        ? "bg-purple-500 text-white"
                        : "text-gray-600 hover:bg-gray-100"
                    }`}
                  >
                    {page}
                  </button>
                ))}
                <button
                  onClick={() => setCurrentPage(Math.min(totalPages, currentPage + 1))}
                  disabled={currentPage === totalPages}
                  className="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  &gt;
                </button>
              </div>
            </div>
          )}
        </div>
      </div>

      <style jsx>{`
        .bg-grid-pattern {
          background-image: linear-gradient(
              to right,
              rgba(107, 33, 168, 0.1) 1px,
              transparent 1px
            ),
            linear-gradient(
              to bottom,
              rgba(107, 33, 168, 0.1) 1px,
              transparent 1px
            );
          background-size: 20px 20px;
        }
      `}</style>
    </div>
  );
}
