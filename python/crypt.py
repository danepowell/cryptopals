import unittest
import array

class CryptoTest(unittest.TestCase):

    def test_challenge_1(self):
        input_hex_str = '49276d206b696c6c696e6720796f757220627261696e206c696b65206120706f69736f6e6f7573206d757368726f6f6d'
        expected_b64_str = 'SSdtIGtpbGxpbmcgeW91ciBicmFpbiBsaWtlIGEgcG9pc29ub3VzIG11c2hyb29t\n'
        self.assertEqual(input_hex_str.decode('hex').encode('base64').decode(), expected_b64_str)

    def test_challenge_2(self):
        input_hex_str1 = '1c0111001f010100061a024b53535009181c'
        input_hex_str2 = '686974207468652062756c6c277320657965'
        expected_hex_str = '746865206b696420646f6e277420706c6179'
        bin_str1 = input_hex_str1.decode('hex')
        bin_str2 = input_hex_str2.decode('hex')
        out = fxor(bin_str1, bin_str2)
        self.assertEqual(out.encode('hex'), expected_hex_str)

    def test_challenge_3(self):
        input_hex_str = '1b37373331363f78151b7f2b783431333d78397828372d363c78373e783a393b3736'
        bin_str = input_hex_str.decode('hex')
        high_score = 0
        score1 = 0
        for i in range(128):
            key = chr(i)
            key_str = key * len(bin_str)
            plaintext = fxor(bin_str, key_str)
            score1 = score(plaintext)
            print score1
            print plaintext
            if score1 > high_score:
                print "candidate found: " + plaintext
                high_score = score1

def fxor(str1, str2):
    arr1 = array.array('B', str1)
    arr2 = array.array('B', str2)
    arr3 = array.array('B', '')
    for i in range(len(arr1)):
        arr3.append(arr1[i] ^ arr2[i])
    return arr3.tostring()

def score(str1):
    str2 = str1.lower()
    if (all(ord(c) < 128 for c in str1)):
        return str2.count("e")
    else:
        return 0

def letter_frequency():
  return array(
    'a' => 0.08167,
    'b' => 0.01492,
    'c' => 0.02782,
    'd' => 0.04253,
    'e' => 0.12702,
    'f' => 0.02228,
    'g' => 0.02015,
    'h' => 0.06094,
    'i' => 0.06966,
    'j' => 0.00153,
    'k' => 0.00772,
    'l' => 0.04025,
    'm' => 0.02406,
    'n' => 0.06749,
    'o' => 0.07507,
    'p' => 0.01929,
    'q' => 0.00095,
    'r' => 0.05987,
    's' => 0.06327,
    't' => 0.09056,
    'u' => 0.02758,
    'v' => 0.00978,
    'w' => 0.02360,
    'x' => 0.00150,
    'y' => 0.01974,
    'z' => 0.00074,
    ' ' => 0.21,
  );
}

if __name__ == '__main__':
    unittest.main()
