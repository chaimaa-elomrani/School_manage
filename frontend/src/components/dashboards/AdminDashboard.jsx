import { useState, useEffect } from 'react';
import { 
  UsersIcon, 
  AcademicCapIcon, 
  BookOpenIcon, 
  CurrencyDollarIcon 
} from '@heroicons/react/24/outline';
import api from '../../services/api';

const AdminDashboard = () => {
  const [stats, setStats] = useState({
    totalStudents: 0,
    totalTeachers: 0,
    totalCourses: 0,
    totalRevenue: 0
  });
  const [recentEnrollments, setRecentEnrollments] = useState([]);
  const [recentPayments, setRecentPayments] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchAdminData();
  }, []);

  const fetchAdminData = async () => {
    try {
      setLoading(true);
      
      console.log('Fetching admin data...'); // Debug log
      
      const [studentsRes, teachersRes, coursesRes, paymentsRes, gradesRes] = await Promise.all([
        api.get('/showStudent').catch(err => {
          console.error('Students API error:', err);
          return { data: { data: [] } };
        }),
        api.get('/showTeacher').catch(err => {
          console.error('Teachers API error:', err);
          return { data: { data: [] } };
        }),
        api.get('/showCourses').catch(err => {
          console.error('Courses API error:', err);
          return { data: { data: [] } };
        }),
        api.get('/showPayments').catch(err => {
          console.error('Payments API error:', err);
          return { data: { data: [] } };
        }),
        api.get('/showGrades').catch(err => {
          console.error('Grades API error:', err);
          return { data: { data: [] } };
        })
      ]);

      console.log('Students response:', studentsRes.data); // Debug log
      console.log('Teachers response:', teachersRes.data); // Debug log
      console.log('Courses response:', coursesRes.data); // Debug log

      const students = studentsRes.data?.data || [];
      const teachers = teachersRes.data?.data || [];
      const courses = coursesRes.data?.data || coursesRes.data || [];
      const payments = paymentsRes.data?.data || paymentsRes.data || [];
      const grades = gradesRes.data?.data || gradesRes.data || [];

      console.log('Processed students:', students); // Debug log
      console.log('Processed teachers:', teachers); // Debug log

      // Calculate total revenue from payments
      const totalRevenue = payments
        .filter(payment => payment.status === 'paid')
        .reduce((sum, payment) => sum + parseFloat(payment.amount || 0), 0);

      setStats({
        totalStudents: students.length,
        totalTeachers: teachers.length,
        totalCourses: courses.length,
        totalRevenue: totalRevenue
      });

      console.log('Final stats:', {
        totalStudents: students.length,
        totalTeachers: teachers.length,
        totalCourses: courses.length,
        totalRevenue: totalRevenue
      }); // Debug log

      // Get recent enrollments (students with recent grades)
      const recentGrades = grades
        .sort((a, b) => new Date(b.created_at || '2024-01-01') - new Date(a.created_at || '2024-01-01'))
        .slice(0, 4);
      
      const enrollments = recentGrades.map(grade => {
        const student = students.find(s => s.id === grade.student_id);
        const course = courses.find(c => c.id === grade.course_id);
        return {
          studentName: student ? `${student.first_name} ${student.last_name}` : 'Unknown Student',
          courseName: course ? course.name : 'Unknown Course',
          initials: student ? `${student.first_name[0]}${student.last_name[0]}` : 'UN'
        };
      });

      setRecentEnrollments(enrollments);

      // Get recent payments
      const recentPaymentsList = payments
        .filter(payment => payment.status === 'paid')
        .sort((a, b) => new Date(b.payment_date) - new Date(a.payment_date))
        .slice(0, 4)
        .map(payment => {
          const student = students.find(s => s.id === payment.student_id);
          return {
            studentName: student ? `${student.first_name} ${student.last_name}` : 'Unknown Student',
            amount: payment.amount,
            date: payment.payment_date,
            initials: student ? `${student.first_name[0]}${student.last_name[0]}` : 'UN'
          };
        });

      setRecentPayments(recentPaymentsList);

    } catch (err) {
      console.error('Admin dashboard error:', err);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
      </div>
    );
  }

  const statCards = [
    {
      title: 'Total Students',
      value: stats.totalStudents,
      icon: UsersIcon,
      color: 'bg-blue-500',
      change: '+12%'
    },
    {
      title: 'Total Teachers',
      value: stats.totalTeachers,
      icon: AcademicCapIcon,
      color: 'bg-green-500',
      change: '+5%'
    },
    {
      title: 'Active Courses',
      value: stats.totalCourses,
      icon: BookOpenIcon,
      color: 'bg-purple-500',
      change: '+8%'
    },
    {
      title: 'Total Revenue',
      value: `$${stats.totalRevenue.toLocaleString()}`,
      icon: CurrencyDollarIcon,
      color: 'bg-yellow-500',
      change: '+15%'
    }
  ];

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <p className="text-gray-600">Welcome back! Here's what's happening at your school.</p>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {statCards.map((stat, index) => {
          const Icon = stat.icon;
          return (
            <div key={index} className="bg-white rounded-lg shadow p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm font-medium text-gray-600">{stat.title}</p>
                  <p className="text-2xl font-bold text-gray-900">{stat.value}</p>
                  <p className="text-sm text-green-600">{stat.change} from last month</p>
                </div>
                <div className={`${stat.color} p-3 rounded-full`}>
                  <Icon className="h-6 w-6 text-white" />
                </div>
              </div>
            </div>
          );
        })}
      </div>

      {/* Recent Activity */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">Recent Enrollments</h3>
          <div className="space-y-3">
            {recentEnrollments.length > 0 ? (
              recentEnrollments.map((enrollment, index) => (
                <div key={index} className="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                  <div className="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                    <span className="text-white text-sm font-medium">{enrollment.initials}</span>
                  </div>
                  <div className="flex-1">
                    <p className="text-sm font-medium text-gray-900">{enrollment.studentName}</p>
                    <p className="text-xs text-gray-500">Enrolled in {enrollment.courseName}</p>
                  </div>
                  <span className="text-xs text-gray-400">Recent</span>
                </div>
              ))
            ) : (
              <p className="text-gray-500 text-center py-4">No recent enrollments</p>
            )}
          </div>
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">Recent Payments</h3>
          <div className="space-y-3">
            {recentPayments.length > 0 ? (
              recentPayments.map((payment, index) => (
                <div key={index} className="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                  <div className="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                    <span className="text-white text-sm font-medium">{payment.initials}</span>
                  </div>
                  <div className="flex-1">
                    <p className="text-sm font-medium text-gray-900">{payment.studentName}</p>
                    <p className="text-xs text-gray-500">Paid ${payment.amount}</p>
                  </div>
                  <span className="text-xs text-gray-400">{new Date(payment.date).toLocaleDateString()}</span>
                </div>
              ))
            ) : (
              <p className="text-gray-500 text-center py-4">No recent payments</p>
            )}
          </div>
        </div>
      </div>

      {/* Quick Actions */}
      <div className="bg-white rounded-lg shadow p-6">
        <h3 className="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <button className="text-left p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
            <p className="font-medium text-blue-900">Add New Student</p>
            <p className="text-sm text-blue-600">Register a new student to the system</p>
          </button>
          <button className="text-left p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
            <p className="font-medium text-green-900">Create Course</p>
            <p className="text-sm text-green-600">Set up a new course curriculum</p>
          </button>
          <button className="text-left p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
            <p className="font-medium text-purple-900">Generate Report</p>
            <p className="text-sm text-purple-600">Create monthly performance reports</p>
          </button>
        </div>
      </div>
    </div>
  );
};

export default AdminDashboard;



