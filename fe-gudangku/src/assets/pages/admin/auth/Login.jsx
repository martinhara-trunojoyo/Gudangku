import { useState } from "react";
import { FaEnvelope, FaLock } from "react-icons/fa";
import { HiEye, HiEyeOff } from "react-icons/hi";
import { Link } from "react-router-dom";

export default function Login() {
  const [showPassword, setShowPassword] = useState(false);

  return (
    <div className="min-h-screen flex items-center justify-center bg-[#eaebf6] px-4">
      <div className="max-w-6xl w-full flex flex-col md:flex-row items-center bg-white rounded-lg overflow-hidden">
        {/* Ilustrasi */}
        <div className="w-full md:w-1/2 flex justify-center p-6">
          <img
            src="img/auth/login.png"
            alt="Login Illustration"
            className="w-80 md:w-full"
          />
        </div>

        {/* Form Login */}
        <div className="w-full md:w-1/2 flex justify-center p-10">
          <div className="w-full max-w-md">
            <h2 className="text-3xl font-bold text-[#6556e8] text-center mb-8">
              Log in
            </h2>

            <form className="space-y-4">
              {/* Email */}
              <div>
                <label className="block text-sm font-medium mb-1">Email</label>
                <div className="flex items-center border border-gray-200 rounded-md px-3 py-2 bg-[#f2f2f2]">
                  <FaEnvelope className="text-gray-500 mr-2" />
                  <input
                    type="email"
                    defaultValue="admin@gmail.com"
                    className="w-full bg-transparent focus:outline-none"
                  />
                </div>
              </div>

              {/* Password */}
              <div>
                <label className="block text-sm font-medium mb-1">Password</label>
                <div className="flex items-center border border-gray-200 rounded-md px-3 py-2 bg-[#f2f2f2]">
                  <FaLock className="text-gray-500 mr-2" />
                  <input
                    type={showPassword ? "text" : "password"}
                    defaultValue="************"
                    className="w-full bg-transparent focus:outline-none"
                  />
                  <button
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="text-gray-500 ml-2 focus:outline-none"
                  >
                    {showPassword ? <HiEyeOff /> : <HiEye />}
                  </button>
                </div>
              </div>

              {/* Remember Me and Forgot Password */}
              <div className="flex items-center justify-between mt-2">
                <div className="flex items-center">
                  <input
                    id="remember"
                    type="checkbox"
                    className="h-4 w-4 text-[#6556e8] focus:ring-[#6556e8] border-gray-300 rounded"
                  />
                  <label
                    htmlFor="remember"
                    className="ml-2 block text-sm text-gray-700"
                  >
                    Remember me
                  </label>
                </div>

                <Link
                  to="/forgot-password"
                  className="text-sm text-[#6556e8] hover:text-[#5849d6] font-medium transition"
                >
                  Forgot Password?
                </Link>
              </div>

              {/* Tombol */}
              <button
                type="submit"
                className="w-full bg-[#6556e8] text-white py-3 rounded-md font-semibold hover:bg-[#5849d6] transition mt-4"
              >
                Log in
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
}
