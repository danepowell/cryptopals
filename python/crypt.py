import array

# Fixed XOR: takes two equal-length binary strings and produces XOR combination.
def fxor(str1, str2):
    assert len(str1) == len(str2), "String lengths must be equal."
    arr1 = array.array('B', str1)
    arr2 = array.array('B', str2)
    for i in range(len(arr1)):
        arr1[i] ^= arr2[i]
    return arr1.tostring()

# Take a string str1 and pad with chars to length.
def str_pad(str1, length, chars):
    repeats = (length // len(chars)) + 1
    pad = chars*repeats
    str1 += pad
    return str1[:length]

# Repeating key XOR: takes a string and repeating key and produces XOR combination.
def rxor(str1, key):
    key = str_pad(key, len(str1), key)
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

# Compute Hamming distance between two strings.
def hamming(str1, str2):
    return sum(tobits(fxor(str1, str2)))

# Convert string to bits (from stackoverflow).
def tobits(s):
    result = []
    for c in s:
        bits = bin(ord(c))[2:]
        bits = '00000000'[len(bits):] + bits
        result.extend([int(b) for b in bits])
    return result

# Guess rxor keysize.
def rxor_keysize(str1):
    keysizes = {}
    for keysize in range(2, 40):
        distance = 0
        blocks = [ str1[keysize*i:keysize*(i+1)] for i in range(4) ]
        for i in range(4):
            for j in range(i):
                if (i != j):
                    distance += hamming(blocks[i], blocks[j])
        keysizes[keysize] = distance / float(keysize)
    return min(keysizes, key=keysizes.get)

# Crack rxor
def rxor_crack(str1, keysize):
    blocks = [ str1[keysize*i:keysize*(i+1)] for i in range(len(str1)/keysize)]
    tblocks = {}
    for i in range(keysize):
        keyblock = ''
        for j in range(len(blocks)):
            keyblock = keyblock + blocks[j][i]
        tblocks[i] = keyblock
    key = ''
    for i in range(keysize):
        key = key + guess_1byte_xor(tblocks[i])['key']
    return key

def pad_pkcs7(str1, length):
    return str_pad(str1, length, "\x04")
