import { create } from "zustand";

export interface Classroom {
  id: string;
  name: string;
  capacity: number;
}

export interface Group {
  id: string;
  name: string;
  studentCount: number;
}

export interface Course {
  id: string;
  name: string;
  type: "core" | "optional";
  groupId: string;
}

export interface ScheduleSlot {
  day: string;
  time: string;
  courseName: string;
  groupName: string;
  classroomName: string;
}

interface Store {
  classrooms: Classroom[];
  groups: Group[];
  courses: Course[];
  schedule: ScheduleSlot[];
  addClassroom: (classroom: Omit<Classroom, "id">) => void;
  addGroup: (group: Omit<Group, "id">) => void;
  addCourse: (course: Omit<Course, "id">) => void;
  generateSchedule: () => void;
}

export const useStore = create<Store>((set, get) => ({
  classrooms: [],
  groups: [],
  courses: [],
  schedule: [],

  addClassroom: (classroom) =>
    set((state) => ({
      classrooms: [...state.classrooms, { ...classroom, id: crypto.randomUUID() }],
    })),

  addGroup: (group) =>
    set((state) => ({
      groups: [...state.groups, { ...group, id: crypto.randomUUID() }],
    })),

  addCourse: (course) =>
    set((state) => ({
      courses: [...state.courses, { ...course, id: crypto.randomUUID() }],
    })),

  generateSchedule: () => {
    const { courses, classrooms, groups } = get();
    const days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
    const times = ["08:00", "10:00", "12:00", "14:00", "16:00"];

    const newSchedule: ScheduleSlot[] = [];

    courses.forEach((course, index) => {
      const group = groups.find((g) => g.id === course.groupId);
      const classroom = classrooms.find(
        (c) => group && c.capacity >= group.studentCount
      );

      if (group && classroom) {
        const dayIndex = index % days.length;
        const timeIndex = Math.floor(index / days.length) % times.length;

        newSchedule.push({
          day: days[dayIndex],
          time: times[timeIndex],
          courseName: course.name,
          groupName: group.name,
          classroomName: classroom.name,
        });
      }
    });

    set({ schedule: newSchedule });
  },
}));
