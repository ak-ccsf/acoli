import React from 'react';
import Navbar from './components/Navbar';
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';
import './App.css';
import Home from './components/pages/Home';
import CostOfLiving from './components/pages/CostOfLiving';
import QualityOfLive from './components/pages/QualityOfLive';
import CityCompare from './components/pages/CityCompare';
import FindYourBestPlace from './components/pages/FindYourBestPlace';

function App() {
  return (
    <Router>
        <Navbar />
        <Switch>
          <Route path='/' exact component={Home} />
          <Route path='/costofliving' component={CostOfLiving} />
          <Route path='/qualityoflive' component={QualityOfLive} />
          <Route path='/citycompare' component={CityCompare} />
          <Route path='/findyourbestplace' component={FindYourBestPlace} />
        </Switch>
    </Router>
  );
}

export default App;
