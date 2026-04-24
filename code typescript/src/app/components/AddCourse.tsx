import { useState } from "react";
import { useNavigate } from "react-router";
import { BookOpen } from "lucide-react";
import { useStore } from "./store";

export function AddCourse() {
  const navigate = useNavigate();
  const { addCourse, groups } = useStore();
  const [name, setName] = useState("");
  const [type, setType] = useState<"core" | "optional">("core");
  const [groupId, setGroupId] = useState("");

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (name && groupId) {
      addCourse({ name, type, groupId });
      setName("");
      setType("core");
      setGroupId("");
      navigate("/view-data");
    }
  };

  return (
    <div className="p-8">
      <div className="max-w-2xl mx-auto">
        <div className="flex items-center gap-3 mb-8">
          <div className="p-3 bg-purple-50 text-purple-600 rounded-lg">
            <BookOpen size={24} />
          </div>
          <h1 className="text-3xl text-gray-900">Add Course</h1>
        </div>

        <div className="bg-white rounded-xl p-8 border border-gray-200 shadow-sm">
          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-2">
                Course Name
              </label>
              <input
                type="text"
                id="name"
                value={name}
                onChange={(e) => setName(e.target.value)}
                placeholder="e.g., Mathematics"
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              />
            </div>

            <div>
              <label htmlFor="type" className="block text-sm font-medium text-gray-700 mb-2">
                Course Type
              </label>
              <select
                id="type"
                value={type}
                onChange={(e) => setType(e.target.value as "core" | "optional")}
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              >
                <option value="core">Core</option>
                <option value="optional">Optional</option>
              </select>
            </div>

            <div>
              <label htmlFor="groupId" className="block text-sm font-medium text-gray-700 mb-2">
                Assigned Group
              </label>
              <select
                id="groupId"
                value={groupId}
                onChange={(e) => setGroupId(e.target.value)}
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              >
                <option value="">Select a group</option>
                {groups.map((group) => (
                  <option key={group.id} value={group.id}>
                    {group.name} ({group.studentCount} students)
                  </option>
                ))}
              </select>
            </div>

            <div className="flex gap-3 pt-4">
              <button
                type="submit"
                className="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
              >
                Add Course
              </button>
              <button
                type="button"
                onClick={() => navigate("/view-data")}
                className="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
              >
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
