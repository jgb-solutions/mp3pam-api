import React from 'react';
import { Link } from 'react-router-dom';
import { Button } from '@material-ui/core';

const Main = props => {
  return (
    <div>
      <nav>
        <ul>
          <li>
            <Button>
              <Link to="/">Home</Link>
            </Button>
          </li>
          <li>
            <Link to="/about">About</Link>
          </li>
        </ul>
      </nav>
      {props.children}
    </div>
  );
};

export default Main;
