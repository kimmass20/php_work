import { Calendar } from "lucide-react";
import { useStore } from "./store";

export function Schedule() {
  const schedule = useStore((state) => state.schedule);

  const days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
  const times = ["08:00", "10:00", "12:00", "14:00", "16:00"];

  const getScheduleSlot = (day: string, time: string) => {
    return schedule.find((slot) => slot.day === day && slot.time === time);
  };

  return (
    <div className="p-8">
      <div className="max-w-7xl mx-auto">
        <div className="flex items-center gap-3 mb-8">
          <div className="p-3 bg-blue-50 text-blue-600 rounded-lg">
            <Calendar size={24} />
          </div>
          <h1 className="text-3xl text-gray-900">Weekly Schedule</h1>
        </div>

        {schedule.length === 0 ? (
          <div className="bg-white rounded-xl p-12 border border-gray-200 shadow-sm text-center">
            <Calendar size={48} className="mx-auto mb-4 text-gray-400" />
            <p className="text-gray-600 mb-2">No schedule generated yet</p>
            <p className="text-sm text-gray-500">
              Go to the Dashboard and click "Generate Schedule" to create one
            </p>
          </div>
        ) : (
          <div className="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full">
                <thead>
                  <tr className="bg-gray-50 border-b border-gray-200">
                    <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                      Time
                    </th>
                    {days.map((day) => (
                      <th
                        key={day}
                        className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        {day}
                      </th>
                    ))}
                  </tr>
                </thead>
                <tbody className="divide-y divide-gray-200">
                  {times.map((time) => (
                    <tr key={time} className="hover:bg-gray-50 transition-colors">
                      <td className="px-4 py-4 text-sm font-medium text-gray-700 bg-gray-50">
                        {time}
                      </td>
                      {days.map((day) => {
                        const slot = getScheduleSlot(day, time);
                        return (
                          <td key={`${day}-${time}`} className="px-4 py-4">
                            {slot ? (
                              <div className="bg-blue-50 border border-blue-200 rounded-lg p-3 hover:bg-blue-100 transition-colors">
                                <div className="font-medium text-sm text-blue-900 mb-1">
                                  {slot.courseName}
                                </div>
                                <div className="text-xs text-blue-700">
                                  {slot.groupName} • {slot.classroomName}
                                </div>
                              </div>
                            ) : (
                              <div className="text-xs text-gray-400 text-center">—</div>
                            )}
                          </td>
                        );
                      })}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
