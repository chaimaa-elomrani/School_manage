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

      {/* Financial Overview Section */}
      <div className="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg shadow p-6 border border-green-200">
        <div className="flex items-center justify-between mb-6">
          <div>
            <h3 className="text-xl font-bold text-green-900">Financial Overview</h3>
            <p className="text-green-600">Revenue and payment tracking</p>
          </div>
          <div className="bg-green-500 p-3 rounded-full">
            <CurrencyDollarIcon className="h-8 w-8 text-white" />
          </div>
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div className="bg-white rounded-lg p-4 shadow-sm border border-green-100">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-gray-600">Total Revenue</p>
                <p className="text-3xl font-bold text-green-600">
                  ${stats.totalRevenue.toLocaleString()}
                </p>
                <p className="text-sm text-green-500 mt-1">+15% from last month</p>
              </div>
              <div className="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <CurrencyDollarIcon className="h-6 w-6 text-green-600" />
              </div>
            </div>
          </div>
          
          <div className="bg-white rounded-lg p-4 shadow-sm border border-blue-100">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-gray-600">Paid Payments</p>
                <p className="text-3xl font-bold text-blue-600">
                  {recentPayments.length}
                </p>
                <p className="text-sm text-blue-500 mt-1">Recent transactions</p>
              </div>
              <div className="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg className="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>
          
          <div className="bg-white rounded-lg p-4 shadow-sm border border-yellow-100">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-gray-600">Avg Payment</p>
                <p className="text-3xl font-bold text-yellow-600">
                  ${recentPayments.length > 0 ? (stats.totalRevenue / recentPayments.length).toFixed(0) : '0'}
                </p>
                <p className="text-sm text-yellow-500 mt-1">Per transaction</p>
              </div>
              <div className="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg className="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Recent Activity */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="bg-white rounded-lg shadow p-6">
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-lg font-semibold text-gray-900">Recent Enrollments</h3>
            <span className="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
              {recentEnrollments.length} New
            </span>
          </div>
          <div className="space-y-3">
            {recentEnrollments.length > 0 ? (
              recentEnrollments.map((enrollment, index) => (
                <div key={index} className="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                  <div className="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-md">
                    <span className="text-white text-sm font-bold">{enrollment.initials}</span>
                  </div>
                  <div className="flex-1">
                    <p className="text-sm font-medium text-gray-900">{enrollment.studentName}</p>
                    <p className="text-xs text-gray-500">Enrolled in {enrollment.courseName}</p>
                  </div>
                  <div className="text-xs text-gray-400">
                    {new Date(enrollment.date).toLocaleDateString()}
                  </div>
                </div>
              ))
            ) : (
              <div className="text-center py-8">
                <div className="w-12 h-12 mx-auto mb-3 bg-gray-100 rounded-full flex items-center justify-center">
                  <svg className="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                </div>
                <p className="text-gray-500 text-sm">No recent enrollments</p>
              </div>
            )}
          </div>
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-lg font-semibold text-gray-900">Recent Payments</h3>
            <span className="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
              ${stats.totalRevenue.toLocaleString()}
            </span>
          </div>
          <div className="space-y-3">
            {recentPayments.length > 0 ? (
              recentPayments.map((payment, index) => (
                <div key={index} className="flex items-center space-x-3 p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100 hover:shadow-md transition-all">
                  <div className="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-md">
                    <span className="text-white text-sm font-bold">{payment.initials}</span>
                  </div>
                  <div className="flex-1">
                    <p className="text-sm font-medium text-gray-900">{payment.studentName}</p>
                    <div className="flex items-center space-x-2">
                      <span className="text-lg font-bold text-green-600">${payment.amount}</span>
                      <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Paid
                      </span>
                    </div>
                  </div>
                  <div className="text-right">
                    <div className="text-xs text-gray-400">
                      {new Date(payment.date).toLocaleDateString()}
                    </div>
                    <div className="text-xs text-green-600 font-medium">
                      Completed
                    </div>
                  </div>
                </div>
              ))
            ) : (
              <div className="text-center py-8">
                <div className="w-12 h-12 mx-auto mb-3 bg-gray-100 rounded-full flex items-center justify-center">
                  <CurrencyDollarIcon className="h-6 w-6 text-gray-400" />
                </div>
                <p className="text-gray-500 text-sm">No recent payments</p>
              </div>
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





