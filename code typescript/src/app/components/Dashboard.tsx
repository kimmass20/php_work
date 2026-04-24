import { DoorOpen, BookOpen, Users, Calendar } from "lucide-react";
import { useStore } from "./store";

export function Dashboard() {
  const { classrooms, groups, courses, generateSchedule } = useStore();

  const stats = [
    {
      label: "Classrooms",
      value: classrooms.length,
      icon: DoorOpen,
      color: "bg-blue-50 text-blue-600",
    },
    {
      label: "Courses",
      value: courses.length,
      icon: BookOpen,
      color: "bg-purple-50 text-purple-600",
    },
    {
      label: "Student Groups",
      value: groups.length,
      icon: Users,
      color: "bg-green-50 text-green-600",
    },
  ];

  return (
    <div className="p-8">
      <div className="max-w-6xl mx-auto">
        <h1 className="text-3xl mb-8 text-gray-900">Gestion des Auditoires</h1>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          {stats.map((stat) => {
            const Icon = stat.icon;
            return (
              <div
                key={stat.label}
                className="bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow"
              >
                <div className="flex items-center justify-between">
                  <div>
                    <p className="text-sm text-gray-600 mb-1">{stat.label}</p>
                    <p className="text-3xl font-semibold text-gray-900">{stat.value}</p>
                  </div>
                  <div className={`p-3 rounded-lg ${stat.color}`}>
                    <Icon size={24} />
                  </div>
                </div>
              </div>
            );
          })}
        </div>

        <div className="bg-white rounded-xl p-8 border border-gray-200 shadow-sm">
          <div className="flex items-center justify-between mb-6">
            <div>
              <h2 className="text-xl font-semibold text-gray-900 mb-2">Schedule Generator</h2>
              <p className="text-sm text-gray-600">
                Automatically generate an optimized schedule based on classrooms, groups, and courses
              </p>
            </div>
          </div>
          <button
            onClick={generateSchedule}
            className="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
          >
            <Calendar size={20} />
            Generate Schedule
          </button>
        </div>
      </div>
    </div>
  );
}
