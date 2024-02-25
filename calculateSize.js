function calculateSize(chest, waist, hips) {
    // Define your size ranges based on measurements
    const sizeRanges = {
      small: { chest: { min: 30, max: 36 }, waist: { min: 24, max: 30 }, hips: { min: 32, max: 38 } },
      medium: { chest: { min: 36, max: 42 }, waist: { min: 30, max: 36 }, hips: { min: 38, max: 44 } },
      large: { chest: { min: 42, max: 48 }, waist: { min: 36, max: 42 }, hips: { min: 44, max: 50 } },
    };
  
    // Check measurements against size ranges
    for (const size in sizeRanges) {
      const { chest: chestRange, waist: waistRange, hips: hipsRange } = sizeRanges[size];
  
      if (
        chest >= chestRange.min && chest <= chestRange.max &&
        waist >= waistRange.min && waist <= waistRange.max &&
        hips >= hipsRange.min && hips <= hipsRange.max
      ) {
        return size;
      }
    }
  
    // If no size is found, return a default size or handle accordingly
    return 'unknown';
  }
  