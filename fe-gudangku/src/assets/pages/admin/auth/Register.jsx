import { useState } from "react";
import { Link } from "react-router-dom";

export default function Register() {
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);

    return (
      <div className="min-h-screen flex items-center justify-center bg-white">
        <div className="w-full max-w-6xl flex flex-col md:flex-row items-center gap-12 p-6 md:p-12">
          {/* Ilustrasi */}
          <div className="w-full md:w-1/2 flex justify-center">
            <img
              src="img/auth/register.png"
              alt="Register Illustration"
              className="w-4/5"
            />
          </div>
  
          {/* Form Registrasi */}
          <div className="w-full md:w-1/2">
            <div className="flex justify-end mb-6">
              <div className="flex items-center gap-2">
                <img src="/img/landing/logo.png" alt="Logo Gudangku" className="w-8 h-8" />
                <span className="font-bold text-lg">Gudangku</span>
              </div>
            </div>
            <h2 className="text-3xl font-bold text-indigo-600 mb-8">Registrasi</h2>
  
            <form className="space-y-5">
              {/* Nama */}
              <div className="space-y-2">
                <label className="block text-sm font-medium text-gray-700">Nama</label>
                <input
                  type="text"
                  placeholder="Nama lengkap"
                  className="w-full border border-gray-300 px-4 py-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500"
                />
              </div>
  
              {/* Username & Email */}
              <div className="flex gap-4">
                <div className="w-1/2 space-y-2">
                  <label className="block text-sm font-medium text-gray-700">Username</label>
                  <input
                    type="text"
                    placeholder="admin"
                    className="w-full border border-gray-300 px-4 py-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500"
                  />
                </div>
                <div className="w-1/2 space-y-2">
                  <label className="block text-sm font-medium text-gray-700">Email</label>
                  <input
                    type="email"
                    placeholder="admin@gmail.com"
                    className="w-full border border-gray-300 px-4 py-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500"
                  />
                </div>
              </div>
  
              {/* Password */}
              <div className="space-y-2">
                <label className="block text-sm font-medium text-gray-700">Password</label>
                <div className="relative">
                  <input
                    type={showPassword ? "text" : "password"}
                    placeholder="••••••••••••••••"
                    className="w-full border border-gray-300 px-4 py-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500"
                  />
                  <button 
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="absolute right-3 top-1/2 -translate-y-1/2"
                  >
                    {showPassword ? (
                      <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                      </svg>
                    ) : (
                      <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    )}
                  </button>
                </div>
              </div>
  
              {/* Confirm Password */}
              <div className="space-y-2">
                <label className="block text-sm font-medium text-gray-700">Confirm Password</label>
                <div className="relative">
                  <input
                    type={showConfirmPassword ? "text" : "password"}
                    placeholder="••••••••••••••••"
                    className="w-full border border-gray-300 px-4 py-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500"
                  />
                  <button 
                    type="button"
                    onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                    className="absolute right-3 top-1/2 -translate-y-1/2"
                  >
                    {showConfirmPassword ? (
                      <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                      </svg>
                    ) : (
                      <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    )}
                  </button>
                </div>
              </div>
  
              {/* Tombol */}
              <button
                type="submit"
                className="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition mt-4"
              >
                Create account
              </button>
  
              {/* Link Login */}
              <div className="text-sm text-center mt-4">
                Already have an account?{" "}
                <Link to="/login" className="text-red-500 font-medium hover:underline">
                  Login
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    );
}
