import { Routes, Route } from 'react-router-dom';
import SignupPage from './pages/signupPage';
import Header from './components/Header';
import Footer from './components/Footer';

const App: React.FC = () => {
  return (
    <div>
      <Header />
      <Routes>
        <Route path="/signup" element={ <SignupPage />} />
      </Routes>
      <Footer />
    </div>

  );
};

export default App;
