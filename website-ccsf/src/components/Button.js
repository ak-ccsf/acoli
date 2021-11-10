import React from "react";
import './Button.css';
import { Link } from 'react-router-dom';

const STYLE = ['btn--primary', 'btn--outline'];

const SIZES = ['btn--medium', 'btn-large'];

export const Button = ( { 
    children, 
    type, 
    onClick, 
    buttonStyle
} ) => {
    const checkButtonStyle = STYLE.includes(buttonStyle) ? buttonStyle : STYLE[0];

    const checkButtonStyle = SIZES.includes(buttonSize) ? buttonSize : SIZES[0];

    return (
        <Link to= '/findyourbestplacequiz' className='btn--mobile'> 
            <bitton className={`btn ${checkButtonStyle} ${checkButtonSize}`}
            onClick={onClick}
            type={type}
            >
                {children}
            </bitton>
            <Button>Get Started</Button>
        </Link>
    )
 };