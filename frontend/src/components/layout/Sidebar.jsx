import { useState } from 'react';
import { useAuth } from '../../contexts/AuthContext';
import {
  HomeIcon,
  UsersIcon,
  AcademicCapIcon,
  BookOpenIcon,
  CalendarIcon,
  DocumentTextIcon,
  CurrencyDollarIcon,
  ChatBubbleLeftRightIcon,
  CogIcon,
  ArrowRightOnRectangleIcon,
  Bars3Icon,
  XMarkIcon,
} from '@heroicons/react/24/outline';

const menuItems = {
  admin: [
    { icon: HomeIcon, label: "Dashboard", path: "/dashboard" },
    { icon: UsersIcon, label: "Students", path: "/students" },
    { icon: AcademicCapIcon, label: "Teachers", path: "/teachers" },
    { icon: BookOpenIcon, label: "Courses", path: "/courses" },
    { icon: CalendarIcon, label: "Schedule", path: "/schedule" },
    { icon: DocumentTextIcon, label: "Reports", path: "/reports" },
    { icon: CurrencyDollarIcon, label: "Finance", path: "/finance" },
    { icon: ChatBubbleLeftRightIcon, label: "Communication", path: "/communication" },
    { icon: CogIcon, label: "Settings", path: "/settings" },
  ],
  teacher: [
    { icon: HomeIcon, label: "Dashboard", path: "/dashboard" },
    { icon: UsersIcon, label: "My Students", path: "/my-students" },
    { icon: BookOpenIcon, label: "My Courses", path: "/my-courses" },
    { icon: CalendarIcon, label: "Schedule", path: "/schedule" },
    { icon: DocumentTextIcon, label: "Grades", path: "/grades" },
    { icon: ChatBubbleLeftRightIcon, label: "Messages", path: "/messages" },
  ],
  student: [
    { icon: HomeIcon, label: "Dashboard", path: "/dashboard" },
    { icon: BookOpenIcon, label: "My Courses", path: "/courses" },
    { icon: CalendarIcon, label: "Schedule", path: "/schedule" },
    { icon: DocumentTextIcon, label: "Grades", path: "/grades" },
    { icon: ChatBubbleLeftRightIcon, label: "Messages", path: "/messages" },
  ],
  parent: [
    { icon: HomeIcon, label: "Dashboard", path: "/dashboard" },
    { icon: UsersIcon, label: "My Children", path: "/children" },
    { icon: DocumentTextIcon, label: "Reports", path: "/reports" },
    { icon: CurrencyDollarIcon, label: "Payments", path: "/payments" },
    { icon: ChatBubbleLeftRightIcon, label: "Messages", path: "/messages" },
  ],
};

export default function Sidebar({ currentPage, onPageChange }) {
  const { user, logout } = useAuth();
  const [isCollapsed, setIsCollapsed] = useState(false);

  const userMenuItems = menuItems[user?.role] || menuItems.admin;

  return (
    <div className={`bg-white border-r border-gray-200 transition-all duration-300 ${isCollapsed ? "w-16" : "w-64"} flex flex-col h-full`}>
      <div className="flex items-center justify-between p-4 border-b border-gray-200">
        {!isCollapsed && <h2 className="text-xl font-bold text-gray-800">School MS</h2>}
        <button
          onClick={() => setIsCollapsed(!isCollapsed)}
          className="p-2 rounded-md hover:bg-gray-100 transition-colors"
        >
          {isCollapsed ? <Bars3Icon className="h-4 w-4" /> : <XMarkIcon className="h-4 w-4" />}
        </button>
      </div>

      <div className="flex-1 p-4 overflow-y-auto">
        <nav className="space-y-2">
          {userMenuItems.map((item) => {
            const Icon = item.icon;
            return (
              <button
                key={item.path}
                className={`w-full flex items-center ${isCollapsed ? 'justify-center px-2' : 'justify-start px-4'} py-2 rounded-md text-sm font-medium transition-colors ${
                  currentPage === item.path
                    ? 'bg-black text-white'
                    : 'text-gray-700 hover:bg-gray-100'
                }`}
                onClick={() => onPageChange(item.path)}
              >
                <Icon className="h-4 w-4" />
                {!isCollapsed && <span className="ml-2">{item.label}</span>}
              </button>
            );
          })}
        </nav>
      </div>

      <div className="p-4 border-t border-gray-200">
        <button
          className={`w-full flex items-center ${isCollapsed ? 'justify-center px-2' : 'justify-start px-4'} py-2 rounded-md text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 transition-colors`}
          onClick={logout}
        >
          <ArrowRightOnRectangleIcon className="h-4 w-4" />
          {!isCollapsed && <span className="ml-2">Logout</span>}
        </button>
      </div>
    </div>
  );
}