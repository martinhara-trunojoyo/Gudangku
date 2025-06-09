import { API } from "../_api";

export const getSupplier = async () => {
  try {
    const token = localStorage.getItem('token');
    const { data } = await API.get("/suppliers", {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    return data.data;
  } catch (error) {
    console.log("Get Supplier error:", error);
    throw error.response ? error.response.data : "An error occurred while fetching Supplier data.";
  }
};

export const createSupplier = async (supplierData) => {
    try {
        const token = localStorage.getItem('token');
        const response = await API.post("/suppliers", {
          nama_supplier: supplierData.name,
          alamat_supplier: supplierData.alamat,
          kontak_supplier: supplierData.kontak
        }, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        return response.data;
    } catch (error) {
        console.log("Create Supplier error:", error);
        throw error.response ? error.response.data : "An error occurred while creating Supplier.";
    }
};

export const showSupplier = async (id) => {
    try {
        const token = localStorage.getItem('token');
        const response = await API.get(`/suppliers/${id}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        return response.data.data;
    } catch (error) {
        console.log("Show Supplier error:", error);
        throw error.response ? error.response.data : "An error occurred while fetching Supplier details.";
    }
};

export const updateSupplier = async (id, supplierData) => {
    try {
        const token = localStorage.getItem('token');
        const response = await API.put(`/suppliers/${id}`, {
          nama_supplier: supplierData.name,
          alamat_supplier: supplierData.alamat,
          kontak_supplier: supplierData.kontak
        }, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        return response.data;
    } catch (error) {
        console.log("Update Supplier error:", error);
        throw error.response ? error.response.data : "An error occurred while updating Supplier.";
    }
};

export const deleteSupplier = async (id) => {
    try {
        const token = localStorage.getItem('token');
        await API.delete(`/suppliers/${id}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
    } catch (error) {
        console.log("Delete Supplier error:", error);
        throw error.response ? error.response.data : "An error occurred while deleting supplier.";
    }
};