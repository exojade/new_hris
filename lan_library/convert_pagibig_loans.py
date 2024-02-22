# Import the required Module
import tabula
from tabula.io import read_pdf
import sys

file_name = sys.argv[1]

from_filename = file_name + ".pdf"
to_filename = file_name + ".csv"

# Read a PDF File
# df = read_pdf("sample.pdf", pages='all', stream=True, guess=False, area=[182, 22, 719, 719])
print(to_filename)
# convert PDF into CSV
tabula.convert_into(from_filename , to_filename, output_format="csv",pages='all', stream=True, guess=False, area=[215, 22, 600, 719])
