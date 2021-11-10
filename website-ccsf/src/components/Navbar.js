import React, {useState} from 'react';
import {Link} from 'react-router-dom';
import './Navbar.css';
import Search from './search.js';

function Navbar() {
    const [click, setClick] = useState(false);
    const [button, setButton] = useState(true);

    const handleClick = () => setClick(!click);
    const closeMobileMenu = () => setClick(false);
    
    //Mobile view
    const showButton = () => {
        if (window.innerWidth <= 960) {
            setButton(false);
        } else {
            setButton(true);
        }
    };


    window.addEventListener('resize', showButton);

    const Navbar = () => {
        return (
            <Search />
        );
    }
    return (
        <>
            <nav className="navbar">
                <div className="navbar-container">
                    <Link to="/" className="navbar-logo">
                    <i className="fas fa-route"></i> BestPlaces
                    </Link>
                    <div className='menu-icon' onClick={handleClick}>
                        <i className={click ? 'fas fa-times' : 'fas fa-bars'} />
                    </div>
                    <ul className={click ? 'nav-menu active' : 'nav-menu'}>
                        <li className='nav-item'>
                            <Link to='/costofliving' className='nav-links' onClick={closeMobileMenu}>
                                Cost of living
                            </Link>
                        </li>
                        <li className='nav-item'>
                            <Link to='/qualityoflive' className='nav-links' onClick={closeMobileMenu}>
                                Quality of live
                            </Link>
                        </li>
                        <li className='nav-item'>
                            <Link to='/citycompare' className='nav-links' onClick={closeMobileMenu}>
                                City Compare
                            </Link>
                        </li>
                        <li className='nav-item'>
                            <Link to='/findyourbestplace' className='nav-links' onClick={closeMobileMenu}>
                                Find your best place quiz
                            </Link>
                        </li>
                        <li className='nav-item'>
                            <form action='/' method='get'>
                                <input
                                    type='search'
                                    className='nav-search'
                                    placeholder='Search...'
                                />
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </>
    )
}

export default Navbar
