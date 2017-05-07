import array

# Fixed XOR: takes two equal-length binary strings and produces XOR combination.
def fxor(str1, str2):
    assert len(str1) == len(str2), "String lengths must be equal."
    arr1 = array.array('B', str1)
    arr2 = array.array('B', str2)
    for i in range(len(arr1)):
        arr1[i] ^= arr2[i]
    return arr1.tostring()

def str_pad(str1, length):
    repeats = (length // len(str1)) + 1
    str1 *= repeats
    return str1[:length]

# Repeating key XOR: takes a string and repeating key and produces XOR combination.
def rxor(str1, key):
    key = str_pad(key, len(str1))
    return fxor(str1, key)

# Guesses 1-byte XOR key for string.
def guess_1byte_xor(str1):
    str_len = len(str1)
    scores = {}
    texts = {}
    for i in range(32, 126):
        key = chr(i)
        key_str = key * str_len
        plaintext = fxor(str1, key_str)
        texts[key] = plaintext
        scores[key] = score(plaintext)
    key = max(scores, key=scores.get)
    high_score = scores[key]
    text = texts[key]
    # print 'key: ' + key
    # print 'text: ' + text
    return {'key': key, 'score': high_score, 'text': text}

# Score a string based on enlish letter frequencies.
def score(str1):
    str1 = str1.lower()
    frequencies = letter_frequencies()
    ss = 0.0
    for letter in frequencies.iterkeys():
        expected_frequency = frequencies[letter]
        actual_frequency = str1.count(letter)
        ss += pow(actual_frequency - expected_frequency, 2)
    return ss

# Take hex-encoded string and convert to base64.
def hex2b64(hex_str):
    return hex_str.decode('hex').encode('base64')

# Return dict of english letter frequencies.
def letter_frequencies():
  return {'a':0.08167, 'b':0.01492, 'c':0.02782, 'd':0.04253, 'e':0.12702, 'f':0.02228, 'g':0.02015, 'h':0.06094, 'i':0.06966, 'j':0.00153, 'k':0.00772, 'l':0.04025, 'm':0.02406, 'n':0.06749, 'o':0.07507, 'p':0.01929, 'q':0.00095, 'r':0.05987, 's':0.06327, 't':0.09056, 'u':0.02758, 'v':0.00978, 'w':0.02360, 'x':0.00150, 'y':0.01974, 'z':0.00074, ' ':0.21}
