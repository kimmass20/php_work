import { useState } from "react";
import { useNavigate } from "react-router";
import { Users } from "lucide-react";
import { useStore } from "./store";

export function AddGroup() {
  const navigate = useNavigate();
  const addGroup = useStore((state) => state.addGroup);
  const [name, setName] = useState("");
  const [studentCount, setStudentCount] = useState("");

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (name && studentCount) {
      addGroup({ name, studentCount: parseInt(studentCount) });
      setName("");
      setStudentCount("");
      navigate("/view-data");
    }
  };

  return (
    <div className="p-8">
      <div className="max-w-2xl mx-auto">
        <div className="flex items-center gap-3 mb-8">
          <div className="p-3 bg-green-50 text-green-600 rounded-lg">
            <Users size={24} />
          </div>
          <h1 className="text-3xl text-gray-900">Add Student Group</h1>
        </div>

        <div className="bg-white rounded-xl p-8 border border-gray-200 shadow-sm">
          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-2">
                Group Name
              </label>
              <select
                id="name"
                value={name}
                onChange={(e) => setName(e.target.value)}
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              >
                <option value="">Select a level</option>
                <option value="L1">L1</option>
                <option value="L2">L2</option>
                <option value="L3">L3</option>
                <option value="L4">L4</option>
              </select>
            </div>

            <div>
              <label htmlFor="studentCount" className="block text-sm font-medium text-gray-700 mb-2">
                Number of Students
              </label>
              <input
                type="number"
                id="studentCount"
                value={studentCount}
                onChange={(e) => setStudentCount(e.target.value)}
                placeholder="e.g., 45"
                min="1"
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              />
            </div>

            <div className="flex gap-3 pt-4">
              <button
                type="submit"
                className="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
              >
                Add Group
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
