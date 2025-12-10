import { createBrowserRouter, Navigate } from "react-router-dom";

// Layouts
import AdminLayout from "./layouts/AdminLayout";
import ManagerLayout from "./layouts/ManagerLayout";
import CashierLayout from "./layouts/CashierLayout";
import GuestLayout from "./layouts/GuestLayout";

// Pages
import Dashboard from "./pages/Dashboard/Dashboard";
import Login from "./pages/Auth/Login";
import Signup from "./pages/Auth/Signup";
import Products from "./pages/Products/ProductList";
import StockMovements from "./pages/Stock/StockMovements";
import SalesList from "./pages/Sales/SalesList";
import NotFound from "./pages/NotFound";

const router = createBrowserRouter([
    // Guest routes
    {
        path: "/",
        element: <GuestLayout />,
        children: [
            { path: "/", element: <Navigate to="/login" /> },
            { path: "login", element: <Login /> },
            { path: "signup", element: <Signup /> },
            { path: "/*", element: <NotFound /> },
        ]
    },

    // Admin routes
    {
        path: "/admin",
        element: <AdminLayout />,
        children: [
            { path: "dashboard", element: <Dashboard /> },
            { path: "products", element: <Products /> },
            { path: "stock", element: <StockMovements /> },
            { path: "sales", element: <SalesList /> },
            { path: "*", element: <NotFound /> },
        ]
    },

    // Manager routes
    {
        path: "/manager",
        element: <ManagerLayout />,
        children: [
            { path: "dashboard", element: <Dashboard /> },
            { path: "products", element: <Products /> },
            { path: "sales", element: <SalesList /> },
            { path: "*", element: <NotFound /> },
        ]
    },

    // Cashier routes
    {
        path: "/cashier",
        element: <CashierLayout />,
        children: [
            { path: "dashboard", element: <Dashboard /> },
            { path: "sales", element: <SalesList /> },
            { path: "*", element: <NotFound /> },
        ]
    },

    // Catch all
    { path: "*", element: <NotFound /> },
]);

export default router;
