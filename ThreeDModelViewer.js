import React, { useRef, useState } from 'react';
import { Canvas } from 'react-three-fiber';
import { useFrame } from '@react-three/fiber';

const Box = ({ scale }) => {
  const mesh = useRef();

  useFrame(() => {
    mesh.current.rotation.x += 0.01;
    mesh.current.rotation.y += 0.01;
  });

  return (
    <mesh ref={mesh} scale={scale}>
      <boxBufferGeometry args={[1, 1, 1]} />
      <meshStandardMaterial color="orange" />
    </mesh>
  );
};

const MeasurementForm = ({ onMeasure }) => {
  const [chest, setChest] = useState('');
  const [waist, setWaist] = useState('');
  const [hips, setHips] = useState('');

  const handleSubmit = (e) => {
    e.preventDefault();
    onMeasure({ chest, waist, hips });
  };

  return (
    <form onSubmit={handleSubmit}>
      <label>
        Chest:
        <input type="text" value={chest} onChange={(e) => setChest(e.target.value)} />
      </label>
      <label>
        Waist:
        <input type="text" value={waist} onChange={(e) => setWaist(e.target.value)} />
      </label>
      <label>
        Hips:
        <input type="text" value={hips} onChange={(e) => setHips(e.target.value)} />
      </label>
      <button type="submit">Submit Measurements</button>
    </form>
  );
};

const ThreeDModelViewer = () => {
  const [measurements, setMeasurements] = useState(null);

  const handleMeasurements = (data) => {
    setMeasurements(data);
  };

  return (
    <div>
      <Canvas>
        <ambientLight intensity={0.5} />
        <pointLight position={[10, 10, 10]} />
        <Box scale={measurements ? [1 + measurements.chest * 0.1, 1 + measurements.waist * 0.1, 1 + measurements.hips * 0.1] : [1, 1, 1]} />
      </Canvas>
      <div>
        {measurements ? (
          <p>
            Measurements: Chest - {measurements.chest}, Waist - {measurements.waist}, Hips -
            {measurements.hips}
          </p>
        ) : (
          <MeasurementForm onMeasure={handleMeasurements} />
        )}
      </div>
    </div>
  );
};

export default ThreeDModelViewer;
