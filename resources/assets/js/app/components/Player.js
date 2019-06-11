import React from 'react';

const Player = props => {
  return (
    <div
      style={{
        position: 'fixed',
        bottom: 0,
        left: 0,
        right: 0,
        height: 86,
        backgroundColor: 'rgba(30, 30, 30)',
        color: 'white'
      }}
    >
      <h3>Audio Player</h3>
    </div>
  );
};

export default Player;
