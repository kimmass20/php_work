import { Outlet, Link, useLocation } from "react-router";
import { LayoutDashboard, DoorOpen, Users, BookOpen, Database, Calendar } from "lucide-react";

export function Layout() {
  const location = useLocation();

  const navItems = [
    { path: "/", label: "Dashboard", icon: LayoutDashboard },
    { path: "/add-classroom", label: "Add Classroom", icon: DoorOpen },
    { path: "/add-group", label: "Add Group", icon: Users },
    { path: "/add-course", label: "Add Course", icon: BookOpen },
    { path: "/view-data", label: "View Data", icon: Database },
    { path: "/schedule", label: "Schedule", icon: Calendar },
  ];

  return (
    <div className="flex h-screen bg-gray-50">
      {/* Sidebar */}
      <aside className="w-64 bg-white border-r border-gray-200 flex flex-col">
        <div className="p-6 border-b border-gray-200">
          <h1 className="font-semibold text-blue-600">Gestion des Auditoires</h1>
        </div>
        <nav className="flex-1 p-4 space-y-1">
          {navItems.map((item) => {
            const Icon = item.icon;
            const isActive = location.pathname === item.path;
            return (
              <Link
                key={item.path}
                to={item.path}
                className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${
                  isActive
                    ? "bg-blue-50 text-blue-600"
                    : "text-gray-700 hover:bg-gray-100"
                }`}
              >
                <Icon size={20} />
                <span className="text-sm font-medium">{item.label}</span>
              </Link>
            );
          })}
        </nav>
      </aside>

      {/* Main Content */}
      <main className="flex-1 overflow-auto">
        <Outlet />
      </main>
    </div>
  );
}
